<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=GroupStage::class, inversedBy="teams")
     */
    private $groupStage;

    /**
     *@ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="team")
     */
    private $players;

    /**
     * @ORM\ManyToOne(targetEntity=Turnament::class, inversedBy="registeredTeams")
     */
    private $turnament;

    /**
     * @ORM\OneToMany(targetEntity=Matches::class, mappedBy="teamA")
     */
    private $matchesA;

    /**
     * @ORM\OneToMany(targetEntity=Matches::class, mappedBy="teamB")
     */
    private $matchesB;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->teamB = new ArrayCollection();
        $this->matches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGroupStage(): ?GroupStage
    {
        return $this->groupStage;
    }

    public function setGroupStage(?GroupStage $groupStage): self
    {
        $this->groupStage = $groupStage;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
    public function computeLevel()
    {
        $lvl=0;
        $count=0;
        foreach($this->getPlayers() as $player)
        {
            $lvl+=($player->getIntLevel()*5)/7;
            $count++;

        }
        $this->setLevel($lvl/$count);

    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    public function getTurnament(): ?Turnament
    {
        return $this->turnament;
    }

    public function setTurnament(?Turnament $turnament): self
    {
        $this->turnament = $turnament;

        return $this;
    }

    /**
     * @return Collection|Matches[]
     */
    public function getMatchesA(): Collection
    {
        return $this->matchesA;
    }

    public function addMatchesA(Matches $matchesA): self
    {
        if (!$this->matchesA->contains($matchesA)) {
            $this->matchesA[] = $matchesA;
            $matchesA->setTeamA($this);
        }

        return $this;
    }

    public function removeMatchesA(Matches $matchesA): self
    {
        if ($this->matchesA->removeElement($matchesA)) {
            // set the owning side to null (unless already changed)
            if ($matchesA->getTeamA() === $this) {
                $matchesA->setTeamA(null);
            }
        }

        return $this;
    }
        /**
     * @return Collection|Matches[]
     */
    public function getMatchesB(): Collection
    {
        return $this->matchesB;
    }

    public function addMatchesB(Matches $matchesB): self
    {
        if (!$this->matchesB->contains($matchesB)) {
            $this->matchesB[] = $matchesB;
            $matchesB->setTeamB($this);
        }

        return $this;
    }

    public function removeMatchesb(Matches $matchesB): self
    {
        if ($this->matchesB->removeElement($matchesB)) {
            // set the owning side to null (unless already changed)
            if ($matchesB->getTeamA() === $this) {
                $matchesB->setTeamA(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

}
