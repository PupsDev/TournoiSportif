<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Player;
use App\Entity\Organizer;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class UserFixtures extends Fixture
{
    protected $encoder;

    public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $player = new Player();
            $player2 = new Player();
            $organizer = new Organizer();

            $player->setEmail("elodie.laborde@wanadoo.fr");
            $player->setPassword( $this->encoder->getEncoder($player)->encodePassword("password", ""));
            $player->setFirstName("Elodie");
            $player->setSurname("Laborde");
            $player->setRoles(array('ROLE_PLAYER'));
            $player->setLevel("Loisir");

            $player2->setEmail("joueur.capitaine@wanadoo.fr");
            $player2->setPassword( $this->encoder->getEncoder($player)->encodePassword("password", ""));
            $player2->setFirstName("Kevin");
            $player2->setSurname("Martin");
            $player2->setRoles(array('ROLE_PLAYER','ROLE_CAPTAIN'));
            $player2->setLevel("Pro");

            $organizer->setEmail("mathieu.ladeuil@umontpellier.fr");
            $organizer->setPassword( $this->encoder->getEncoder($organizer)->encodePassword("password", ""));
            $organizer->setFirstName("Mathieu");
            $organizer->setSurname("Ladeuil");
            $organizer->setStructure("Solary");
            $organizer->setRoles(array('ROLE_ORGANIZER'));

            $manager->persist($player);
            $manager->persist($player2);
            $manager->persist($organizer);
            $manager->flush();
    	
    }
}
