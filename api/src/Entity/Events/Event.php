<?php

namespace App\Entity\Events;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\DTO\Events\EventInput;
use App\DTO\Events\EventOutput;
use App\Entity\Game;
use App\Repository\Events\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
#[
    ApiResource(
        collectionOperations: [
        "get",
        "post" => [
            "method" => "post",
            "path" => "/events",
            "openapi_context" => [
                'summary' => 'Create an event resource',
                "requestBody" => [
                    "content" => [
                        "application/json" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "datetime" => ["type" => "integer"],
                                    "booster" => ["type" => "integer"],
                                    "eventType" => ["type" => "integer"],
                                    "faction" => ["type" => "integer"],
                                    "region" => ["type" => "integer"],
                                    "game" => ["type" => "integer"],
                                    "slotsLimit" => ["type" => "integer"],
                                    "isLocked" => ["type" => "boolean"],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ],
        "batch_create_events" => [
            "method" => "post",
            "path" => "/events/batch-create",
            "controller" => "App\Controller\Events\EventController::batchPostEvents",
            "pagination_enabled" => false,
            "validate" => false,
            "normalization_context" => [
                "skip_null_values" => true
            ],
            "input" => false
        ],
    ],
        itemOperations: [
        "get",
        "put",
        "delete",
    ],
        attributes: [
        "pagination_client_items_per_page" => true,
        "order" => ["datetime" => "ASC"]
    ],
        denormalizationContext: [
        "groups" => ["events:write"]
    ],
        input: EventInput::class,
        normalizationContext: [
        "skip_null_values" => true,
        "groups" => ["events:read"],
        "datetime_format" => "Y-m-d H:i:s"
    ],
        output: EventOutput::class,
    )
]
#[ApiFilter(SearchFilter::class, properties: [
    "game" => "exact",
    "region" => "exact",
    "faction" => "exact",
    "booster" => "exact",
    "eventType" => "exact",
])]
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"events:read", "mythicKeyLogs:read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"events:read", "events:write"})
     * @Assert\NotBlank()
     */
    #[ApiProperty(
        openapiContext: [
            "type" => "datetime",
            "example" => "Y-m-d H:i:s"
        ]
    )]
    private \DateTimeInterface $datetime;

    /**
     * @ORM\ManyToOne(targetEntity=Booster::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"events:read", "events:write"})
     * @Assert\NotBlank(message="Booster not found")
     */
    private $booster;

    /**
     * @ORM\ManyToOne(targetEntity=EventType::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"events:read", "events:write"})
     * @Assert\NotBlank(message="EventType not found", allowNull="false")
     */
    private $eventType;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"events:read", "events:write"})
     * @Assert\NotBlank()
     */
    private int $slotsLimit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"events:read", "events:write"})
     */
    private $customDuration;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"events:read", "events:write"})
     * @Assert\NotNull
     */
    private bool $isLocked = false;

    /**
     * @ORM\ManyToOne(targetEntity=Faction::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"events:read", "events:write"})
     * @Assert\NotBlank(message="Faction not found")
     */
    private $faction;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"events:read", "events:write"})
     * @Assert\NotBlank(message="Region not found")
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"events:read", "events:write"})
     * @Assert\NotBlank(message="Game not found")
     */
    private $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getBooster(): ?Booster
    {
        return $this->booster;
    }

    public function setBooster(?Booster $booster): self
    {
        $this->booster = $booster;

        return $this;
    }

    public function getEventType(): ?EventType
    {
        return $this->eventType;
    }

    public function setEventType(?EventType $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getSlotsLimit(): ?int
    {
        return $this->slotsLimit;
    }

    public function setSlotsLimit(int $slotsLimit): self
    {
        $this->slotsLimit = $slotsLimit;

        return $this;
    }

    public function getCustomDuration(): ?int
    {
        return $this->customDuration;
    }

    public function setCustomDuration(?int $customDuration): self
    {
        $this->customDuration = $customDuration;

        return $this;
    }

    public function getIsLocked(): ?bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function getFaction(): ?Faction
    {
        return $this->faction;
    }

    public function setFaction(?Faction $faction): self
    {
        $this->faction = $faction;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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
