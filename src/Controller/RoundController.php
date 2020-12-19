<?php

namespace App\Controller;

use App\Entity\Round;
use App\Form\RoundType;
use App\Repository\RoundRepository;
use App\Repository\TurnamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @Route("/event/{idEvent}/turnaments/{idTurnament}/round")
 */
class RoundController extends AbstractController
{
    /**
     * @Route("/", name="round_index", methods={"GET"})
     */
    public function index(TurnamentRepository $turnamentRepository, RoundRepository $roundRepository, int $idTurnament ): Response
    {
        $turnament = $turnamentRepository->findById($idTurnament);


        return $this->render('round/index.html.twig', [
            'rounds' => $turnament[0]->getRounds(),
            
        ]);
    }

    /**
     * @Route("/new", name="round_new", methods={"GET","POST"})
     */
    public function new(Request $request,TurnamentRepository $turnamentRepository,int $idTurnament): Response
    {
        $turnament = $turnamentRepository->findById($idTurnament);
        $event = $turnament[0]->getEvent();

        $round = new Round();
        
        $round->setTurnament($turnament[0]);
        $originalGroupStages = new ArrayCollection();
        $entityManager = $this->getDoctrine()->getManager();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($round->getGroupStages() as $groupStage) {
            $originalGroupStages->add($groupStage);
        }

        $form = $this->createForm(RoundType::class, $round);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalGroupStages as $groupStage) {
                if (false === $round->getGroupStages()->contains($groupStage)) {
                    // remove the Task from the Tag
                    $groupStage->getRound()->removeElement($round);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $entityManager->persist($groupStage);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }
                 }

            $entityManager->persist($round);
            $entityManager->flush();
           // return $this->redirectToRoute('round_index');
        }

        return $this->render('round/new.html.twig', [
            'round' => $round,
            'form' => $form->createView(),
            'turnament' => $turnament[0],
        ]);
    }
        /**
     * @Route("/save", name="round_save")
     */
    public function save(Request $request):Response
    {
        $response = new Response();
        
        if ($request->isXMLHttpRequest()) {
            $data = $request->request->get('groupStageJson');
            //print_r($data);
             print_r($data);
           /* $itemName = // Extract it from $data variable

            $item = NewItem();
            $item->setItemName($itemName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();*/

             $response->setContent("ok");
            
        } else {
            //echo("nope");
            $response->setContent("error");
        }
           return $response;
    }

    /**
     * @Route("/{id}", name="round_show", methods={"GET"})
     */
    public function show(Round $round): Response
    {
        return $this->render('round/show.html.twig', [
            'round' => $round,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="round_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Round $round,TurnamentRepository $turnamentRepository,int $idTurnament): Response
    {
        $turnament = $turnamentRepository->findById($idTurnament);
        $event = $turnament[0]->getEvent();

        
        $round->setTurnament($turnament[0]);
        $originalGroupStages = new ArrayCollection();
        $entityManager = $this->getDoctrine()->getManager();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($round->getGroupStages() as $groupStage) {
            $originalGroupStages->add($groupStage);
        }

        $form = $this->createForm(RoundType::class, $round);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalGroupStages as $groupStage) {
                if (false === $round->getGroupStages()->contains($groupStage)) {
                    // remove the Task from the Tag
                    $groupStage->getRound()->removeElement($round);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $entityManager->persist($groupStage);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }
                 }

            $entityManager->persist($round);
            $entityManager->flush();
           // return $this->redirectToRoute('round_index');
        }

        return $this->render('round/edit.html.twig', [
            'round' => $round,
            'form' => $form->createView(),
            'turnament' => $turnament[0],
        ]);
    }

    /**
     * @Route("/{id}", name="round_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Round $round): Response
    {
        if ($this->isCsrfTokenValid('delete'.$round->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($round);
            $entityManager->flush();
        }

        return $this->redirectToRoute('round_index');
    }


}

