<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Player;
use App\Entity\Organizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventRepository;
use App\Repository\TurnamentRepository;
use App\Entity\Event;
use App\Entity\Turnament;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class HomeController extends AbstractController
{
	protected $em;
    protected $encoder;

    public function __construct(EntityManagerInterface $em, EncoderFactoryInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        
        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
      /**
     * @Route("/register", name="app_register")
     */
    public function register(): Response
    {

        
        return $this->render('home/register.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/search/query={query}", name="searchAction", methods={"GET"})
     */
    public function searchAction($query, TurnamentRepository $repoTurnament, EventRepository $repoEvent):Response
    {

        $data =$query;


        $donneesT = $repoTurnament->search($data);
        $donneesE = $repoEvent->search($data);
        foreach($donneesE as $event)
        {
            foreach ($event->getTurnaments() as $turnament) {
                array_push($donneesT,$turnament);
            }
            
        }


    return $this->render('home/search.html.twig', array(
        'turnaments' => $donneesT,
        'query' => $query,
    ));
        
    }
    /**
     * @Route("/turnaments", name="turnaments_index_all", methods={"GET"})
     */
    public function turnaments(): Response
    {
       $turnaments = $this->em->getRepository("App:Turnament")->findAll();
        return $this->render('event/indexAll.html.twig', [
            'turnaments' => $turnaments,
        ]);
    }
    /**
     * @Route("/logged", name="logged_home")
     */
    public function logged(): Response
    {

    	return $this->render('home/logged.html.twig');
    }
}
