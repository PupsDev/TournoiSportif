<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Team;
use App\Entity\Matches;
use App\Entity\Turnament;
use App\Entity\Round;
use App\Services\TurnamentManager;
use App\Form\TurnamentType;
use App\Repository\TurnamentRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event/{idEvent}/turnaments")
 */
class TurnamentController extends AbstractController
{
    /**
     * @Route("/", name="turnament_index", methods={"GET"})
     */
    public function index(TurnamentRepository $turnamentRepository, EventRepository $eventRepository, int $idEvent ): Response
    {
        $event = $eventRepository->findById($idEvent);


        return $this->render('turnament/index.html.twig', [
            'turnaments' => $event[0]->getTurnaments(),
            
        ]);
    }
    /**
     * @Route("/{id}", name="turnament_show", methods={"GET"})
     */
    public function show(Turnament $turnament, EventRepository $eventRepository, int $idEvent ): Response
    {
         $event = $eventRepository->findById($idEvent);

        return $this->render('turnament/show.html.twig', [
            'turnament' => $turnament,
            
        ]);
    }
  
    /**
     * @Route("/{id}/finish", name="turnament_finish", methods={"GET"})
     */
    public function finish(Turnament $turnament, EventRepository $eventRepository, int $idEvent ): Response
    {

        $consolanteID=null;
         $id = $turnament->getId();
         foreach($turnament->getRounds() as $key => $round)
         {
             if($round->getLoserBracket())
            {
                 $consolanteID = $round->getLoserBracketRound()->getId();
            }

         }
         $rankPrincipal =  array();
         $rankConsolante =  array();

         try
        {
            $principalJ = file_get_contents("tournoi".$id.".json");
            $principalJ = json_decode($principalJ);
            foreach ($principalJ->teamsRanking as $key ) {
             $team = $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find($key->id);
            array_push($rankPrincipal, $team);
           
         }
        }
        catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
        if($consolanteID)
        {
          try
        
            {
                $consolanteJ = file_get_contents("consolante".($consolanteID).".json");
                $consolanteJ = json_decode($consolanteJ);
                 foreach ($consolanteJ->teamsRanking as $key ) {
                 $team = $this->getDoctrine()
                            ->getRepository(Team::class)
                            ->find($key->id);
                array_push($rankConsolante, $team);
               
             }
            }
            catch (Exception $e) {
               echo 'Exception reçue : ',  $e->getMessage(), "\n";
            }
        }

         
    
         

        return $this->render('turnament/finish.html.twig', [
            'turnament' => $turnament,
            'rankPrincipal' => $rankPrincipal,
            'rankConsolante' => $rankConsolante,
            
        ]);
    }
   /**
     * @Route("/{id}/player/round={roundId}", name="turnament_show_player", methods={"GET"})
     */
    public function showPlayer(Turnament $turnament, EventRepository $eventRepository, int $idEvent,int $roundId ): Response
    {
         $event = $eventRepository->findById($idEvent);

        return $this->render('turnament/show_player.html.twig', [
            'turnament' => $turnament,
            'roundId' => $roundId,
            
        ]);
    }
    /**
     * @Route("/{id}/run/round={roundId}", name="turnament_run", methods={"GET","POST"})
     */
    public function run(Turnament $turnament, EventRepository $eventRepository, int $idEvent,Request $request, int $roundId): Response
    {
         $entityManager = $this->getDoctrine()->getManager();
         $event = $eventRepository->findById($idEvent);
         $groupModified = false;

         $currentRound = $roundId;
        $rounds = $turnament->getRounds();
        $consolanteID=null;
         foreach($rounds as $round)
         {
            if($round->getLoserBracket())
            {
                 $consolanteID = $round->getLoserBracketRound()->getId();
            }

         }


         $round = $turnament->getRounds()[$currentRound];
         

         
        if ($request->isXMLHttpRequest()) {
            
             $data = $request->request->get('dataMatchesJson');
             $groupModified = true;
             $response = new Response();
             $data = json_decode($data);
             $scoresA = $data->scoreA;
             $scoresB = $data->scoreB;

            
             $groupStagesNew = $data->groupStages;
             $idsA = $data->idA;
             $idsB = $data->idB;
             $idSet = $data->idSet;
             $idGroup = $data->idGroup;

            foreach ($scoresA as $key => $value) {
                $match = new Matches();
                $scoreB = $scoresB[$key];
                $match->setScoreTeamA(intval($value));
                $match->setScoreTeamB(intval($scoreB));

                $teamA = $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find($idsA[$key]);
                $teamB = $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find($idsB[$key]);

                $match->setTeamA($teamA);
                $match->setTeamB($teamB);
                $match->setSetIndex($idSet[$key]);
                $match->setGroupStageIndex($idGroup[$key]-1);

                $teamA->addMatchesA($match);
                $teamB->addMatchesB($match);


                $entityManager->persist($teamA);
                $entityManager->persist($teamB);
                $entityManager->persist($match);
            }
             $entityManager->flush();
             $tm = new TurnamentManager();

           
            if($rounds[$currentRound-1] && $rounds[$currentRound-1]->getLoserBracket())
            { 
                 $conso = true;
                 $idJson = $rounds[$currentRound-1]->getLoserBracketRound()->getId();

            }
            else{
                $conso = false;
                $idJson = "";
            }
             $teamsRanked = $tm->getRanking($scoresA,$scoresB, $idGroup,$idSet,$idsA, $idsB, $conso  , $idJson);


             if($round->getLastRound())
                $data->last = true;
             else
                $data->last = false;
              if($round->getLoserBracket())
             {
                
                $currentRound++;
               
             }
            
             if(!($round->getId() == $consolanteID) && !$round->getLastRound())
             {
                 $maxBest = $round->getPlayerPerRoundStage();

        
             $nextRound = $turnament->getRounds()[$currentRound+1];

             $nextGroupStage = $nextRound->getGroupStages();

             $nextGroupStageDouble = $nextRound->getGroupStagesDouble();
            
            $diff = array_diff($nextGroupStage->toArray(), $nextGroupStageDouble->toArray());


            $nextGroupStage = new ArrayCollection($diff);


             $teams = new ArrayCollection();


             foreach($teamsRanked['teamsRanking'] as $teamR)
             {
                $team = $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find($teamR['id']);
                $teams->add($team);
             }
              $j=0;
            $p = sizeof($nextGroupStage)-1;
            while($j<$maxBest)
            {
                
                for($i =0 ; $i <= $p ;$i++)
                {
                    if( $teams[$j])
                    {
                        
                        $nextGroupStage[ $p-$i]->addTeam($teams[$j]);
                        $teams[$j]->setGroupStage($nextGroupStage[ $p-$i]);
                        $entityManager->persist($nextGroupStage[ $p-$i]);
                        $entityManager->persist($teams[$j]);

                        $j++;

                    }
                }
                for($i = $p ; $i >=  0;$i--)
                {
                    if( $teams[$j])
                    {
                                                
                        $nextGroupStage[ $p-$i]->addTeam($teams[$j]);
                        $teams[$j]->setGroupStage($nextGroupStage[ $p-$i]);
                        $entityManager->persist($nextGroupStage[ $p-$i]);
                        $entityManager->persist($teams[$j]);

                        $j++;

                    }
                }

            }
             //SI LOSER BRACKET
             if($nextRound->getLoserBracket())
             {
                $loser = $nextRound->getLoserBracketRound();

                //1 seule poule
                $gp = $loser->getGroupStages()[0];

                for($i=$maxBest ; $i< sizeof($teamsRanked['teamsRanking']);$i++)
                 {
                    $gp->addTeam($teams[$i]);
                    $teams[$i]->setGroupStage($gp);
                    $entityManager->persist($gp);
                    $entityManager->persist($teams[$i]);

                 }
                 $entityManager->persist($loser);

             }
             else if($nextRound->getDoubleElimination())
             {
                
                $i=0;
                for($j=$maxBest ; $j< sizeof($teamsRanked['teamsRanking']);$j++)
                {
                    if($i == sizeof($nextGroupStageDouble))
                    $i=0;
                    if($teams[$j])
                    {
                        $nextGroupStageDouble[$i]->addTeam($teams[$j]);
                        $teams[$j]->setGroupStage($nextGroupStageDouble[$i]);
                        $entityManager->persist($nextGroupStageDouble[$i]);
                        $entityManager->persist($teams[$j]);
                        $i++;


                    }

                }
                
                  

             }
             
             $entityManager->persist($nextRound);

             }
             
                if($round->getLastRound())
                {

                 $file = 'tournoi'.$turnament->getId().'.json';
                 file_put_contents($file, json_encode($teamsRanked));

                }
                else if($round->getId() == $consolanteID)
                {
                    $file = 'consolante'.$turnament->getId().'.json';
                 file_put_contents($file, json_encode($teamsRanked));
                } 
                
             

             
             $data->teams = $teamsRanked;

             $entityManager->flush();
            $response->setContent(json_encode([
                'data' => $data,
            ]));
              return $response;
         }
        
        //dump($currentRound);
        if($currentRound==0)
        {
            //On récupère toutes les équipes du tournoi triées par le niveau
            $teams = $turnament->getRegisteredTeams();
           

            //Méthode Serpentin
       
            $groupStages = $round->getGroupStages();

             $j=0;
            $p = sizeof($groupStages)-1;
            while($j<sizeof($teams))
            {
                
                for($i =0 ; $i <= $p ;$i++)
                {
                    if( $teams[$j])
                    {
                        
                        $groupStages[ $p-$i]->addTeam($teams[$j]);
                        $teams[$j]->setGroupStage($groupStages[ $p-$i]);
                        $entityManager->persist($groupStages[ $p-$i]);
                        $entityManager->persist($teams[$j]);

                        $j++;

                    }
                }
                for($i = $p ; $i >=  0;$i--)
                {
                    if( $teams[$j])
                    {
                                                
                        $groupStages[ $p-$i]->addTeam($teams[$j]);
                        $teams[$j]->setGroupStage($groupStages[ $p-$i]);
                        $entityManager->persist($groupStages[ $p-$i]);
                        $entityManager->persist($teams[$j]);

                        $j++;

                    }
                }


                

            }

           
            $entityManager->persist($round);
            $currentRound++;

        }

        $entityManager->flush();
        return $this->render('turnament/run.html.twig', [
            'turnament' => $turnament,
            'rounds' => $round,
            'roundId' => $roundId,

            
        ]);
    }
    /**
     * @Route("/{id}/run-binary/round={roundId}", name="turnament_run_binary", methods={"GET","POST"})
     */
    public function run_binary(Turnament $turnament, EventRepository $eventRepository, int $idEvent,Request $request, int $roundId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
         $event = $eventRepository->findById($idEvent);
         $groupModified = false;

         $currentRound = $roundId;
        $rounds = $turnament->getRounds();
        $round = $turnament->getRounds()[$currentRound];

        if($currentRound==0)
        {
            //On récupère toutes les équipes du tournoi triées par le niveau
            $teams = $turnament->getRegisteredTeams();

            //Méthode Serpentin
       
            $groupStages = $round->getGroupStages();

            $i = 0; $j=0;
            while($j!=sizeof($teams))
            {
                if($i == sizeof($groupStages))
                    $i=0;
                if($teams[$j])
                {
                    $groupStages[$i]->addTeam($teams[$j]);
                    $teams[$j]->setGroupStage($groupStages[$i]);
                    $entityManager->persist($groupStages[$i]);
                    $entityManager->persist($teams[$j]);
                    $i++;
                    $j++;

                }

            }
           
            $entityManager->persist($round);
             $currentRound++;

        }

        $entityManager->flush();

            return $this->render('turnament/run_binary.html.twig', [
            'turnament' => $turnament,
            'rounds' => $round,
            'roundId' => $roundId,

            
        ]);
    }

    /**
     * @Route("/{id}/edit", name="turnament_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Turnament $turnament, EventRepository $eventRepository, int $idEvent ): Response
    {
        //$event = $eventRepository->findById($idEvent);
        $originalRounds = new ArrayCollection();
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($turnament->getRounds() as $round) {
            $originalRounds->add($round);

        }

        $form = $this->createForm(TurnamentType::class, $turnament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalRounds as $round) {
                if(is_null($round->getDoubleElimination()) )
                    {
                        $round->setDoubleElimination(false);
                    }
                if (false === $turnament->getRounds()->contains($round)) {
                    // remove the Task from the Tag
                    
                    $round->getTurnament()->removeElement($turnament);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);
                    
                    $entityManager->persist($round);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }
            }


            $entityManager->persist($turnament);
            $entityManager->flush();


            return $this->redirectToRoute('turnament_show', ['turnament' => $turnament, 'idEvent' => $idEvent, 'id' => $turnament->getId() ]);
        }

        return $this->render('turnament/edit.html.twig', [
            'turnament' => $turnament,
            'form' => $form->createView(),
            
        ]);
    }
    



}
