<?php

namespace App\Entity\Events;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Game;
use App\Repository\Events\EventTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventTypeRepository::class)
 */
#[
    ApiResource(
        collectionOperations: [
        "get" => ["path" => "/event-types"],
        "post" => [
            "method" => "post",
            "path" => "/event-types",
            "openapi_context" => [
                'summary' => 'Create a event-type resource',
                "requestBody" => [
                    "content" => [
                        "application/json" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "name" => ["type" => "string"],
                                    "isActive" => ["type" => "boolean"],
                                    "game" => ["type" => "integer"],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ],
    ],
        itemOperations: [
        "get" => ["path" => "/event-types/{id}"],
        "put" => [
            "method" => "put",
            "path" => "/event-types/{id}",
            "openapi_context" => [
                'summary' => 'Update a event-type resource',
                "requestBody" => [
                    "content" => [
                        "application/json" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "name" => ["type" => "string"],
                                    "isActive" => ["type" => "boolean"],
                                    "game" => ["type" => "integer"],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ],
        "delete" => ["path" => "/event-types/{id}"],
    ],
        attributes: [
        "pagination_client_items_per_page" => true
    ],
        denormalizationContext: [
        "groups" => "eventTypes:write"
    ],
        normalizationContext: [
        "skip_null_values" => true,
        "groups" => "eventTypes:read"
    ],
    ),
]
#[ApiFilter(SearchFilter::class, properties: [
    "game" => "exact",
])]
class EventType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"eventTypes:read", "events:read", "eventAttributes"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"eventTypes:read", "eventTypes:write", "events:read", "eventAttributes"})
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"eventTypes:read", "eventTypes:write"})
     * @Assert\NotNull()
     */
    private bool $isActive = true;

    /**
     * @ORM\Column(type="integer")
     */
    private int $sort = 99999;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $defaultDuration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $defaultSlotLimit = 10;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="eventType")
     */
    private $events;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="eventTypes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"eventTypes:read", "eventTypes:write", "eventTypeAttributes:read"})
     * @Assert\NotBlank
     */
    private $game;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getDefaultDuration(): ?int
    {
        return $this->defaultDuration;
    }

    public function setDefaultDuration(?int $defaultDuration): self
    {
        $this->defaultDuration = $defaultDuration;

        return $this;
    }

    public function getDefaultSlotLimit(): ?int
    {
        return $this->defaultSlotLimit;
    }

    public function setDefaultSlotLimit(?int $defaultSlotLimit): self
    {
        $this->defaultSlotLimit = $defaultSlotLimit;

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
            $event->setEventType($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getEventType() === $this) {
                $event->setEventType(null);
            }
        }

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
