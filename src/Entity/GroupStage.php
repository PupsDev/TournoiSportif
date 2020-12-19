<?php

namespace App\Entity;

use App\Repository\GroupStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupStageRepository::class)
 */
class GroupStage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="groupStage",cascade={"persist", "remove"})
     */
    private $teams;

    /**
     * @ORM\ManyToOne(targetEntity=Round::class, inversedBy="groupStages",cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $round;

    /**
     * @ORM\Column(type="integer")
     */
    private $setPerGroupStage;

    /**
     * @ORM\Column(type="integer")
     */
    private $setPoints;

    /**
     * @ORM\ManyToOne(targetEntity=Round::class, inversedBy="groupStagesDouble")
     */
    private $roundDouble;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $playerPerGroupStage;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setGroupStage($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getGroupStage() === $this) {
                $team->setGroupStage(null);
            }
        }

        return $this;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): self
    {
        $this->round = $round;

        return $this;
    }


    public function getSetPerGroupStage(): ?int
    {
        return $this->setPerGroupStage;
    }

    public function setSetPerGroupStage(int $setPerGroupStage): self
    {
        $this->setPerGroupStage = $setPerGroupStage;

        return $this;
    }

    public function getSetPoints(): ?int
    {
        return $this->setPoints;
    }

    public function setSetPoints(int $setPoints): self
    {
        $this->setPoints = $setPoints;

        return $this;
    }

    public function getRoundDouble(): ?Round
    {
        return $this->roundDouble;
    }

    public function setRoundDouble(?Round $roundDouble): self
    {
        $this->roundDouble = $roundDouble;

        return $this;
    }
    public function __toString()
    {
        return strval($this->id);
    }

    public function getPlayerPerGroupStage(): ?int
    {
        return $this->playerPerGroupStage;
    }

    public function setPlayerPerGroupStage(?int $playerPerGroupStage): self
    {
        $this->playerPerGroupStage = $playerPerGroupStage;

        return $this;
    }
}
