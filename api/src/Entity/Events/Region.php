<?php

namespace App\Entity\Events;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Events\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 */
#[
    ApiResource(
        collectionOperations: [
        "get" => ["path" => "/regions"],
    ],
        itemOperations: [
        "get" => ["path" => "/regions/{id}"],
    ],
        attributes: [
        "pagination_client_items_per_page" => true,
        "force_eager" => true,
    ],
        denormalizationContext: [
        "groups" => ["regions:write"]
    ],
        normalizationContext: [
        "skip_null_values" => true,
        "groups" => ["regions:read"]
    ],
    )
]
class Region
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"regions:read", "events:read", "eventAttributes"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"regions:read", "regions:write", "events:read", "eventAttributes"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="region")
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
            $event->setRegion($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getRegion() === $this) {
                $event->setRegion(null);
            }
        }

        return $this;
    }
}
