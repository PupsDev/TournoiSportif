<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *@ORM\Column(name="level", type="string", columnDefinition="enum('Pro', 'Elite', 'N2', 'N3', 'Régional', 'Départemental','Loisir')")
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="players")
     */
    private $team;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }
    public function getIntLevel(): ?int
    {
        $levels = ['Pro'=>1, 'Elite'=>2, 'N2'=>3, 'N3'=>4, 'Régional'=>5, 'Départemental'=>6,'Loisir'=>7];
        return $levels[$this->getLevel()];

    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
     public function __toString() {
        return $this->name;
    }


}
