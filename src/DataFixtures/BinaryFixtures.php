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

class BinaryFixtures extends Fixture
{
    protected $encoder;

    public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $organizer = new Organizer();
        $organizer->setEmail("mathieu.ladeuil@gmail.com");
        $organizer->setPassword( $this->encoder->getEncoder($organizer)->encodePassword("password", ""));
        $organizer->setFirstName("Mathieu");
        $organizer->setSurname("Ladeuil");
        $organizer->setStructure("Fnatic");
        $organizer->setRoles(array('ROLE_ORGANIZER'));

    	$date = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-07-14 14:00:00");
    	$event = new Event();
    	$event->setName("BIGENDIAN Binary");
    	$event->setSport("sports_esports");
    	$event->setDate($date);
    	$event->setLocation("Montpellier");
    	$event->setPlayerPerTeam(3);
        $event->setOrganizer($organizer);
        $event->setTeamPerTurnament(16);
        $organizer->addEvent($event);

    	$turnament = new Turnament();
    	$turnament->setName("Tournoi Binaire 16 équipes");

    	$levels = [5,1,4,3,2,5,1,5,1,4,3,2,5,1,4,2];

    	//16 équipes dans le tournois
    	for($i = 0 ; $i <16; $i++)
    	{
    		$team = new Team();
            // a -> ?
    		$team->setName(chr(97 + $i));
    		$team->setLevel($levels[$i]);
    		$turnament->addRegisteredTeam($team);
    		$team->setTurnament($turnament);
    		$manager->persist($team);

    	}

    	$startingTeams = 16;
        $numberRounds = log ( $startingTeams , 2 );
         foreach (range(0, $numberRounds-1) as $i) {
             $round = new Round();
             $startingTeams/=2;
            //On prends les 4 meilleurs du premier tour
            $round->setPlayerPerRoundStage($startingTeams);
            $round->setLoserBracket(false);
            if($i == $numberRounds-1)
                $round->setLastRound(true);
            else
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
                $manager->persist($groupStage);
            }
            $turnament->addRound($round);
            $round->setTurnament($turnament);
            $manager->persist($round);
        }
        $event->addTurnament($turnament);
        $turnament->setEvent($event);
        $manager->persist($turnament);
        $event->setOrganizer($organizer);
        $organizer->addEvent($event);

    	$manager->persist($turnament);
    	$manager->persist($event);
        $manager->persist($organizer);

        $manager->flush();
    }
}
