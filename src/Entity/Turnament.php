<?php

namespace App\Entity;

use App\Repository\TurnamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity(repositoryClass=TurnamentRepository::class)
 */
class Turnament
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
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="turnaments", cascade={"persist"})
     * @ORM\JoinColumn(name="Event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity=Round::class, mappedBy="turnament", cascade={"persist","remove"})
     */
    private $rounds;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="turnament")
     */
    private $registeredTeams;




    public function __construct()
    {
        $this->rounds = new ArrayCollection();
        $this->name="";
        $this->registeredTeams = new ArrayCollection();
        $active = false;
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


    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection|Round[]
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function addRound(Round $round): self
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds[] = $round;
            $round->setTurnament($this);
        }

        return $this;
    }

    public function removeRound(Round $round): self
    {
        if ($this->rounds->removeElement($round)) {
            // set the owning side to null (unless already changed)
            if ($round->getTurnament() === $this) {
                $round->setTurnament(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Team[]
     */
    public function getRegisteredTeams(): Collection
    {
        $criteria = Criteria::create()
                        ->orderBy(array('level'=>'DSC'));

        return $this->registeredTeams->matching($criteria);
    }

    public function addRegisteredTeam(Team $registeredTeam): self
    {
        if (!$this->registeredTeams->contains($registeredTeam)) {
            $this->registeredTeams[] = $registeredTeam;
            $registeredTeam->setTurnament($this);
        }

        return $this;
    }

    public function removeRegisteredTeam(Team $registeredTeam): self
    {
        if ($this->registeredTeams->removeElement($registeredTeam)) {
            // set the owning side to null (unless already changed)
            if ($registeredTeam->getTurnament() === $this) {
                $registeredTeam->setTurnament(null);
            }
        }

        return $this;
    }

}
