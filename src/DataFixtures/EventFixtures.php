<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Event;
use App\Entity\Turnament;
use App\Entity\Round;
use App\Entity\GroupStage;
use App\Entity\Team;
use App\Entity\Organizer;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EventFixtures extends Fixture
{
    protected $encoder;

    public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $organizer = new Organizer();
        $organizer->setEmail("pierre.pompidor@umontpelier.fr");
        $organizer->setPassword( $this->encoder->getEncoder($organizer)->encodePassword("password", ""));
        $organizer->setFirstName("Pierre");
        $organizer->setSurname("Pompidor");
        $organizer->setStructure("LIRMM");
        $organizer->setRoles(array('ROLE_ORGANIZER'));

        $listeSports = ["sports_basketball","sports_tennis","sports_cricket"];
        for($i=0;$i<3;$i++)
        {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-07-".($i+5)." 14:00:00");
            $event = new Event();
            $event->setName("Evenement Sportif ".($i+1));
            $event->setSport($listeSports[$i]);
            $event->setDate($date);
            $event->setLocation("Montpellier");
            $event->setPlayerPerTeam(3);
            $event->setOrganizer($organizer);
            $event->setTeamPerTurnament(16);
            $organizer->addEvent($event);

            $turnament = new Turnament();
            $turnament->setName("Tournoi Sportif ".($i+1));


            
            $event->addTurnament($turnament);
            $turnament->setEvent($event);
            $manager->persist($turnament);

        }
    	
        $event->setOrganizer($organizer);
        $organizer->addEvent($event);

    	$manager->persist($turnament);
    	$manager->persist($event);
        $manager->persist($organizer);

        $manager->flush();
    }
}
