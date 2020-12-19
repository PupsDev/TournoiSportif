<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerPerTeam;

    /**
     * @ORM\OneToMany(targetEntity=Turnament::class, mappedBy="event", cascade={"persist","remove"})

     */
    private $turnaments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sport;

    /**
     * @ORM\ManyToOne(targetEntity=Organizer::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organizer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $teamPerTurnament;

    public function __construct()
    {
        $this->turnaments = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPlayerPerTeam(): ?int
    {
        return $this->playerPerTeam;
    }

    public function setPlayerPerTeam(int $playerPerTeam): self
    {
        $this->playerPerTeam = $playerPerTeam;

        return $this;
    }

    /**
     * @return Collection|Turnament[]
     */
    public function getTurnaments(): Collection
    {
        return $this->turnaments;
    }

    public function addTurnament(Turnament $turnament): self
    {
        if (!$this->turnaments->contains($turnament)) {
            $this->turnaments[] = $turnament;
            $turnament->setEvent($this);
        }

        return $this;
    }

    public function removeTurnament(Turnament $turnament): self
    {
        if ($this->turnaments->removeElement($turnament)) {
            // set the owning side to null (unless already changed)
            if ($turnament->getEvent() === $this) {
                $turnament->setEvent(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->name;
    }

    public function getSport(): ?string
    {
        return $this->sport;
    }

    public function setSport(string $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    public function getOrganizer(): ?Organizer
    {
        return $this->organizer;
    }

    public function setOrganizer(?Organizer $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getTeamPerTurnament(): ?int
    {
        return $this->teamPerTurnament;
    }

    public function setTeamPerTurnament(?int $teamPerTurnament): self
    {
        $this->teamPerTurnament = $teamPerTurnament;

        return $this;
    }
}
