<?php

namespace App\Controller;

use App\Entity\Event;

use App\Entity\Turnament;
use App\Entity\Round;
use App\Entity\GroupStage;
use App\Form\TurnamentType;
use App\Controller\TurnamentController;
use App\Repository\TurnamentRepository;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $originalTurnaments = new ArrayCollection();
        $entityManager = $this->getDoctrine()->getManager();

        

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($event->getTurnaments() as $turnament) {
            $originalTurnaments->add($turnament);
        }

        $form = $this->createForm(EventType::class, $event);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalTurnaments as $turnament) {
                if (false === $event->getTurnaments()->contains($turnament)) {
                    // remove the Task from the Tag
                    $turnament->getEvent()->removeElement($event);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $entityManager->persist($turnament);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }
                 }
            $event->setOrganizer($this->getUser());
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/new-binary", name="event_new_binary", methods={"GET","POST"})
     */
    public function new_binary(Request $request): Response
    {
        $event = new Event();
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($event)
                    ->add('name', TextType::class,[
                        'attr'=>[
                            'placeholder' => "Nom de l'événement"
                        ]
                    ])
                    ->add('date', DateTimeType::class,[
                        'attr'=>[
                            'placeholder' => "Date de l'événement"
                        ]
                    ])
                    ->add('location', TextType::class,[
                        'attr'=>[
                            'placeholder' => "Lieu de l'événement"
                        ]
                    ])
                    ->add('sport', ChoiceType::class, [
                
                          'choices' => 
                         [
                          'Football' => 'sports_soccer',
                          'Basketball' => 'sports_basketball',
                          'Tennis' => 'sports_tennis',
                          'Volley' => 'sports_volleyball',
                          'Pétanque' => 'sports_baseball',
                          'Baseball' => 'sports_cricket',
                          'Esport' => 'sports_esports',

                         ]
                         
                      ])
                    ->add('playerPerTeam', RangeType::class, [
                          'attr' => [
                              'min' => 1,
                              'max' => 11,
                              'value' => 5
                          ]
                    ])
                     ->add('teamPerTurnament', TextType::class,[
                        'attr'=>[
                            'placeholder' => "Nombre d'équipes du tournoi"
                        ]
                    ])
            ->getForm();
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
         $turnament = new Turnament();
         $turnament->setName("Principal Binaire");
         $startingTeams = intval($event->getTeamPerTurnament());
         $numberRounds = log ( $startingTeams , 2 );
         foreach (range(0, $numberRounds-1) as $i) {
             $round = new Round();
             $startingTeams/=2;
            //On prends les 4 meilleurs du premier tour
            $round->setPlayerPerRoundStage($startingTeams);
            $round->setLoserBracket(false);
            $round->setLastRound(false);
            $round->setDoubleElimination(false);

            foreach(range(0, $startingTeams-1) as $j)
            {
                $groupStage = new GroupStage();
                $groupStage->setPlayerPerGroupStage(2);
                $groupStage->setSetPerGroupStage(1);
                $groupStage->setSetPoints(25);

                $groupStage->setRound($round);
                $round->addGroupStage($groupStage);
                $entityManager->persist($groupStage);
            }
            $turnament->addRound($round);
            $round->setTurnament($turnament);
            $entityManager->persist($round);
             
        }
        $event->addTurnament($turnament);
        $turnament->setEvent($event);
        $entityManager->persist($turnament);
        $event->setOrganizer($this->getUser());
        $entityManager->persist($event);
        $entityManager->flush();
        dump($event);
        return $this->redirectToRoute('event_index');
        

        }
        return $this->render('event/new_binary.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
            
    }


    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $originalTurnaments = new ArrayCollection();
        $entityManager = $this->getDoctrine()->getManager();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($event->getTurnaments() as $turnament) {
            $originalTurnaments->add($turnament);
        }

        $form = $this->createForm(EventType::class, $event);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalTurnaments as $turnament) {
                if (false === $event->getTurnaments()->contains($turnament)) {
                    // remove the Task from the Tag
                    $turnament->getEvent()->removeElement($event);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $entityManager->persist($turnament);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }

            }
            dump($event);

            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/add_turnament", name="add_turnament", methods={"GET","POST"})
     */
    public function add_turnament(Request $request, Event $event, int $id): Response
    {
       $turnament = new Turnament();
        $form = $this->createForm(TurnamentType::class, $turnament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            /*$entityManagerTurnament = $event->getDoctrine()->getManager();
            $event->addTurnament($turnament);

            $entityManagerTurnament->persist($event);
            $entityManagerTurnament->flush();*/
            
            $entityManager->persist($turnament);
            $entityManager->flush();

            return $this->redirectToRoute('turnament_index', ['event' => $event, ]);
        }

        return $this->render('turnament/new.html.twig', [
            'turnament' => $turnament,
            'form' => $form->createView(),
            'event' => $event, 

        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }
}
