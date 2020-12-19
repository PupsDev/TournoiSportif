<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchesRepository::class)
 */
class Matches
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreTeamA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreTeamB;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="teamB")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teamA;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="matches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teamB;

    /**
     * @ORM\Column(type="integer")
     */
    private $setIndex;

    /**
     * @ORM\Column(type="integer")
     */
    private $groupStageIndex;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoreTeamA(): ?int
    {
        return $this->scoreTeamA;
    }

    public function setScoreTeamA(?int $scoreTeamA): self
    {
        $this->scoreTeamA = $scoreTeamA;

        return $this;
    }

    public function getScoreTeamB(): ?int
    {
        return $this->scoreTeamB;
    }

    public function setScoreTeamB(?int $scoreTeamB): self
    {
        $this->scoreTeamB = $scoreTeamB;

        return $this;
    }

    public function getTeamA(): ?Team
    {
        return $this->teamA;
    }

    public function setTeamA(?Team $teamA): self
    {
        $this->teamA = $teamA;

        return $this;
    }

    public function getTeamB(): ?Team
    {
        return $this->teamB;
    }

    public function setTeamB(?Team $teamB): self
    {
        $this->teamB = $teamB;

        return $this;
    }

    public function getSetIndex(): ?int
    {
        return $this->setIndex;
    }

    public function setSetIndex(int $setIndex): self
    {
        $this->setIndex = $setIndex;

        return $this;
    }

    public function getGroupStageIndex(): ?int
    {
        return $this->groupStageIndex;
    }

    public function setGroupStageIndex(int $groupStageIndex): self
    {
        $this->groupStageIndex = $groupStageIndex;

        return $this;
    }
}
