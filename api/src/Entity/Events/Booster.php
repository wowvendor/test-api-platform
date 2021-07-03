<?php

namespace App\Entity\Events;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Game;
use App\Repository\Events\BoosterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=BoosterRepository::class)
 */
#[
    ApiResource(
        collectionOperations: [
        "get",
        "post" => [
            "method" => "post",
            "path" => "/boosters",
            "openapi_context" => [
                'summary' => 'Create a booster resource',
                "requestBody" => [
                    "content" => [
                        "application/json" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "name" => ["type" => "string"],
                                    "isActive" => ["type" => "boolean"],
                                    "games" => [
                                        "type" => "array",
                                        "items" => [
                                            "type" => "object",
                                            "properties" => [
                                                "id" => ["type" => "integer"],
                                            ]
                                        ]
                                    ],
                                    "parent" => ["type" => "integer"],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ],
    ],
        itemOperations: [
        "get",
        "put" => [
            "method" => "put",
            "path" => "/boosters/{id}",
            "openapi_context" => [
                'summary' => 'Update a booster resource',
                "requestBody" => [
                    "content" => [
                        "application/json" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "name" => ["type" => "string"],
                                    "isActive" => ["type" => "boolean"],
                                    "games" => [
                                        "type" => "array",
                                        "items" => [
                                            "type" => "object",
                                            "properties" => [
                                                "id" => ["type" => "integer"],
                                            ]
                                        ]
                                    ],
                                    "parent" => ["type" => "integer"],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ],
        "delete",
    ],
        attributes: [
        "pagination_client_items_per_page" => true,
        "force_eager" => true,
    ],
        denormalizationContext: [
        "groups" => ["boosters:write"]
    ],
        normalizationContext: [
        "skip_null_values" => true,
        "groups" => ["boosters:read"]
    ],
    )
]
#[ApiFilter(BooleanFilter::class, properties: ["isActive"])]
#[ApiFilter(SearchFilter::class, properties: [
    "parent" => "exact",
    "games" => "exact",
    "name" => "partial",
])]
class Booster
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"boosters:read", "events:read", "mythicKeys:read", "eventAttributes", "mythicKeyAttributes"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"boosters:write", "boosters:read", "events:read", "mythicKeys:read", "eventAttributes", "mythicKeyAttributes"})
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"boosters:write", "boosters:read"})
     */
    private bool $isActive = true;

    /**
     * @ORM\Column(type="integer")
     */
    private int $sort = 99999;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, inversedBy="boosters")
     * @Groups({"boosters:read", "boosters:write"})
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "You have to select at least 1 game"
     * )
     */
    private $games;

    /**
     * @ORM\ManyToOne(targetEntity=self::class, inversedBy="children")
     * @return ?self
     * @MaxDepth(1)
     * @Groups({"boosters:read", "boosters:write", "events:read"})
     */
    #[ApiProperty(
        readableLink: true,
        writableLink: true,
        openapiContext: [
            "type" => "object",
            "properties" => [
                "id" => [
                    "type" => "integer"
                ],
                "name" => [
                    "type" => "string"
                ],
                "isActive" => [
                    "type" => "boolean"
                ],
                "games" => [
                    "type" => "array",
                    "items" => [
                        "\$ref" => "#/components/schemas/Game-games.read"
                    ]
                ],
            ]
        ]
    )]
    public ?self $parent;

    /**
     * @ORM\OneToMany(targetEntity=self::class, mappedBy="parent")
     * @Groups ({"eventAttributes"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="booster")
     */
    private $events;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->games = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        $this->games->removeElement($game);

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
            $event->setBooster($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getBooster() === $this) {
                $event->setBooster(null);
            }
        }

        return $this;
    }
}
