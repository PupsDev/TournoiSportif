<?php

namespace App\Controller;

use App\Entity\Organizer;
use App\Form\OrganizerType;
use App\Repository\OrganizerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;


/**
 * @Route("/organizer")
 */
class OrganizerController extends AbstractController
{
    protected $encoder;

    public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @Route("/", name="organizer_index", methods={"GET"})
     */
    public function index(OrganizerRepository $organizerRepository): Response
    {
        return $this->render('organizer/index.html.twig', [
            'organizers' => $organizerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="organizer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $organizer = new Organizer();
        $form = $this->createForm(OrganizerType::class, $organizer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $password = $this->encoder->getEncoder($player)->encodePassword($player->getPassword(), "");

            $player->setPassword($password);
            $player->setRoles(array('ROLE_USER'));
            $entityManager->persist($organizer);
            $entityManager->flush();

            return $this->redirectToRoute('organizer_index');
        }

        return $this->render('organizer/new.html.twig', [
            'organizer' => $organizer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="organizer_show", methods={"GET"})
     */
    public function show(Organizer $organizer): Response
    {
        return $this->render('organizer/show.html.twig', [
            'organizer' => $organizer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="organizer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Organizer $organizer): Response
    {
        $form = $this->createForm(OrganizerType::class, $organizer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('organizer_index');
        }

        return $this->render('organizer/edit.html.twig', [
            'organizer' => $organizer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="organizer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Organizer $organizer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organizer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($organizer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('organizer_index');
    }
}
