<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Events\Booster;
use App\Entity\Events\Event;
use App\Entity\Events\EventType;
use App\Entity\Events\LootLimit;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get"={"path"="/games/{id}"}
 *     },
 *     collectionOperations={
 *         "get"
 *     },
 *     normalizationContext={
 *         "skip_null_values" = true,
 *         "groups"="games:read"
 *     },
 *     denormalizationContext={
 *         "groups"="games:write"
 *     },
 *     attributes={
 *         "pagination_client_items_per_page"=true
 *     }
 * )
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"games:read", "boosters:read", "boosters:write", "eventTypes:read", "eventTypes:write", "events:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"games:read", "boosters:read", "eventTypes:read", "events:read"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Booster::class, mappedBy="game")
     */
    private $boosters;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="game")
     */
    private $events;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"games:read", "boosters:read", "eventTypes:read", "events:read"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=EventType::class, mappedBy="game")
     */
    private $eventTypes;

    public function __construct()
    {
        $this->boosters = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->eventTypes = new ArrayCollection();
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
     * @return Collection|Booster[]
     */
    public function getBoosters(): Collection
    {
        return $this->boosters;
    }

    public function addBooster(Booster $booster): self
    {
        if (!$this->boosters->contains($booster)) {
            $this->boosters[] = $booster;
            $booster->addGame($this);
        }

        return $this;
    }

    public function removeBooster(Booster $booster): self
    {
        if ($this->boosters->removeElement($booster)) {
            $booster->removeGame($this);
        }

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
            $event->setGame($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getGame() === $this) {
                $event->setGame(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|EventType[]
     */
    public function getEventTypes(): Collection
    {
        return $this->eventTypes;
    }

    public function addEventType(EventType $eventType): self
    {
        if (!$this->eventTypes->contains($eventType)) {
            $this->eventTypes[] = $eventType;
            $eventType->setGame($this);
        }

        return $this;
    }

    public function removeEventType(EventType $eventType): self
    {
        if ($this->eventTypes->removeElement($eventType)) {
            // set the owning side to null (unless already changed)
            if ($eventType->getGame() === $this) {
                $eventType->setGame(null);
            }
        }

        return $this;
    }
}
