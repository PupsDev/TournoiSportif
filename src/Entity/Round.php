<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoundRepository::class)
 */
class Round
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity=GroupStage::class, mappedBy="round",cascade={"persist","remove"})
     */
    private $groupStages;

    /**
     * @ORM\ManyToOne(targetEntity=Turnament::class, inversedBy="rounds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $turnament;

     /**
     * @ORM\Column(type="integer")
     */
    private $playerPerRoundStage;

    /**
     * @ORM\OneToOne(targetEntity=Round::class, cascade={"persist", "remove"})
     */
    private $loserBracketRound;

    /**
     * @ORM\Column(type="boolean")
     */
    private $loserBracket;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lastRound;

    /**
     * @ORM\Column(type="boolean")
     */
    private $doubleElimination;

    /**
     * @ORM\OneToMany(targetEntity=GroupStage::class, mappedBy="roundDouble")
     */
    private $groupStagesDouble;

    public function __construct()
    {
        $this->groupStages = new ArrayCollection();
        $this->groupStagesDouble = new ArrayCollection();
        $this->getDoubleElimination = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|GroupStage[]
     */
    public function getGroupStages(): Collection
    {
        return $this->groupStages;
    }

    public function addGroupStage(GroupStage $groupStage): self
    {
        if (!$this->groupStages->contains($groupStage)) {
            $this->groupStages[] = $groupStage;
            $groupStage->setRound($this);
        }

        return $this;
    }

    public function removeGroupStage(GroupStage $groupStage): self
    {
        if ($this->groupStages->removeElement($groupStage)) {
            // set the owning side to null (unless already changed)
            if ($groupStage->getRound() === $this) {
                $groupStage->setRound(null);
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
       public function getPlayerPerRoundStage(): ?int
    {
        return $this->playerPerRoundStage;
    }

    public function setPlayerPerRoundStage(int $playerPerRoundStage): self
    {
        $this->playerPerRoundStage = $playerPerRoundStage;

        return $this;
    }

    public function getLoserBracketRound(): ?self
    {
        return $this->loserBracketRound;
    }

    public function setLoserBracketRound(?self $loserBracketRound): self
    {
        $this->loserBracketRound = $loserBracketRound;

        return $this;
    }

    public function getLoserBracket(): ?bool
    {
        return $this->loserBracket;
    }

    public function setLoserBracket(bool $loserBracket): self
    {
        $this->loserBracket = $loserBracket;

        return $this;
    }

    public function getLastRound(): ?bool
    {
        return $this->lastRound;
    }

    public function setLastRound(bool $lastRound): self
    {
        $this->lastRound = $lastRound;

        return $this;
    }

    public function getDoubleElimination(): ?bool
    {
        return $this->doubleElimination;
    }

    public function setDoubleElimination(bool $doubleElimination): self
    {
        $this->doubleElimination = $doubleElimination;

        return $this;
    }

    /**
     * @return Collection|GroupStage[]
     */
    public function getGroupStagesDouble(): Collection
    {
        return $this->groupStagesDouble;
    }

    public function addGroupStagesDouble(GroupStage $groupStagesDouble): self
    {
        if (!$this->groupStagesDouble->contains($groupStagesDouble)) {
            $this->groupStagesDouble[] = $groupStagesDouble;
            $groupStagesDouble->setRoundDouble($this);
        }

        return $this;
    }

    public function removeGroupStagesDouble(GroupStage $groupStagesDouble): self
    {
        if ($this->groupStagesDouble->removeElement($groupStagesDouble)) {
            // set the owning side to null (unless already changed)
            if ($groupStagesDouble->getRoundDouble() === $this) {
                $groupStagesDouble->setRoundDouble(null);
            }
        }

        return $this;
    }
}
