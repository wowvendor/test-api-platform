<?php

namespace App\Entity\Events;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Events\FactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FactionRepository::class)
 */
#[
    ApiResource(
        collectionOperations: [
        "get" => ["path" => "/factions"],
    ],
        itemOperations: [
        "get" => ["path" => "/factions/{id}"],
    ],
        attributes: [
        "pagination_client_items_per_page" => true,
        "force_eager" => true,
    ],
        denormalizationContext: [
        "groups" => ["factions:write"]
    ],
        normalizationContext: [
        "skip_null_values" => true,
        "groups" => ["factions:read"]
    ],
    )
]
class Faction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"factions:read", "events:read", "eventAttributes"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"factions:read", "factions:write", "events:read", "eventAttributes"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="faction")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
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

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setFaction($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getFaction() === $this) {
                $event->setFaction(null);
            }
        }

        return $this;
    }
}
