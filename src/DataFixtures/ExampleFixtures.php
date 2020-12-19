<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Event;
use App\Entity\Player;
use App\Entity\Turnament;
use App\Entity\Round;
use App\Entity\GroupStage;
use App\Entity\Team;
use App\Entity\Organizer;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ExampleFixtures extends Fixture
{
    protected $encoder;

    public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $organizer = new Organizer();
        $organizer->setEmail("elodie.Laborde@umontpellier.fr");
        $organizer->setPassword( $this->encoder->getEncoder($organizer)->encodePassword("password", ""));
        $organizer->setFirstName("elodie");
        $organizer->setSurname("Laborde");
        $organizer->setStructure("Mash");
        $organizer->setRoles(array('ROLE_ORGANIZER'));

    	$date = \DateTime::createFromFormat('Y-m-d H:i:s', "2021-07-11 13:00:00");
    	$event = new Event();
    	$event->setName("Tournoi multi sport Montpellier");
    	$event->setSport("sports_petanque");
    	$event->setDate($date);
    	$event->setLocation("Montpellier");
    	$event->setPlayerPerTeam(3);
        $event->setOrganizer($organizer);
        $event->setTeamPerTurnament(4);
        $organizer->addEvent($event);
       

    	$turnament = new Turnament();
    	$turnament->setName("Principal 4 équipes");

    	$levels = [5,1,4,3,2,5,1];

    	//4 équipes dans le tournois
    	for($i = 0 ; $i <4; $i++)
    	{
    		$team = new Team();
            // a -> g
    		$team->setName(chr(97 + $i));
    		$team->setLevel($levels[$i]);
    		$turnament->addRegisteredTeam($team);
    		$team->setTurnament($turnament);
            for ($j=0; $j < 2; $j++) { 
                $player=new Player();
                $player->setEmail("mail".$i.$j."@gmail.com");
                $player->setPassword( $this->encoder->getEncoder($organizer)->encodePassword("password", ""));
                $player->setFirstName("Jeanne");
                $player->setSurname("Marianne");
                $player->setTeam($team);
                $player->setLevel("Régional");
                $team->addPlayer($player);
                $manager->persist($player);
            }
    		$manager->persist($team);

    	}



    	$round = new Round();

        //On prends les 4 meilleurs du premier tour
        $round->setPlayerPerRoundStage(4);
        $round->setLoserBracket(false);
        $round->setLastRound(false);
        $round->setDoubleElimination(false);

    	// 1er tour -> 2 Poules
    	$groupStage = new GroupStage();

        //1 set de 25 points
    	$groupStage->setSetPerGroupStage(1);
        $groupStage->setSetPoints(25);

    	$groupStage2 = new GroupStage();

        //2 set de 25 points
    	$groupStage2->setSetPerGroupStage(2);
        $groupStage2->setSetPoints(25);

    	$groupStage->setRound($round);
    	$groupStage2->setRound($round);

    	$round->addGroupStage($groupStage);
    	$round->addGroupStage($groupStage2);

    	$round2 = new Round();

        $round2->setPlayerPerRoundStage(2);
        $round2->setLastRound(false);
        $round2->setDoubleElimination(false);

    	// 2er tour -> 2 Poules
    	$groupStage3 = new GroupStage();
    	$groupStage4 = new GroupStage();

        //chaque poule a 1 set de 25 points
    	$groupStage3->setSetPerGroupStage(1);
    	$groupStage4->setSetPerGroupStage(1);
        $groupStage3->setSetPoints(25);
        $groupStage4->setSetPoints(25);

    	$groupStage3->setRound($round2);
    	$groupStage4->setRound($round2);

    	$round2->addGroupStage($groupStage3);
    	$round2->addGroupStage($groupStage4);

        //Un tour de consolante associé au tour 2
        $roundConsolante = new Round();
        $roundConsolante->setLoserBracket(false);
        $roundConsolante->setLastRound(true);
        $roundConsolante->setDoubleElimination(false);
        $roundConsolante->setPlayerPerRoundStage(3);

        $groupStage5 = new GroupStage();

        //Dans la consolante 1 set de 25 points
        $groupStage5->setSetPerGroupStage(1);
        $groupStage5->setSetPoints(25);
        $groupStage5->setRound($roundConsolante);

        $round2->setLoserBracket(true);
        $round2->setLoserBracketRound($roundConsolante);

        //3éme tour 
        $round3 = new Round();
        $round3->setLastRound(true);
        $round3->setDoubleElimination(true);
        $groupStage6 = new GroupStage();

        $groupStageDouble = new GroupStage();

        $groupStage6->setSetPerGroupStage(1);
        $groupStage6->setSetPoints(25);
        $groupStage6->setRound($round3);

        $groupStageDouble->setSetPerGroupStage(1);
        $groupStageDouble->setSetPoints(25);
        $groupStageDouble->setRound($round3);
        $groupStageDouble->setRoundDouble($round3);

        $round3->addGroupStage($groupStage6);

        $round3->addGroupStagesDouble($groupStageDouble);
        $round3->setLoserBracket(false);
        $round3->setPlayerPerRoundStage(2);

    	$turnament->addRound($round);
    	$turnament->addRound($round2);
        $turnament->addRound($round3);
        $roundConsolante->setTurnament($turnament);
    	$round->setTurnament($turnament);
    	$round2->setTurnament($turnament);
        $round3->setTurnament($turnament);


    	$turnament->setEvent($event);
    	$event->addTurnament($turnament);


        //Et on persiste dans la bdd
    	$manager->persist($groupStage);
    	$manager->persist($groupStage2);
    	$manager->persist($groupStage3);
    	$manager->persist($groupStage4);
        $manager->persist($groupStage5);
        $manager->persist($groupStage6);

        $manager->persist($groupStageDouble);

    	$manager->persist($round);
    	$manager->persist($round2);
        $manager->persist($round3);
        $manager->persist($roundConsolante);

    	$manager->persist($turnament);
    	$manager->persist($event);
        $manager->persist($organizer);

        $manager->flush();
    }
}
