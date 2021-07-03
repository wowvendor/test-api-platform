<?php

namespace App\DTO\Events;

use App\Attribute as Validator;
use App\Entity\Events\Booster;
use App\Entity\Events\EventType;
use App\Entity\Events\Faction;
use App\Entity\Events\Region;
use App\Entity\Game;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AssertBPM;

class EventInput
{
    #[Groups(["events:read", "events:write"])]
    #[Assert\NotBlank]
    #[Assert\DateTime(format: 'U')]
    #[Validator\NoCollapse]
    public ?int $datetime;

    #[Groups(["events:read", "events:write"])]
    #[AssertBPM\Identifier(['entityClass' => Booster::class])]
    public ?int $booster;

    #[Groups(["events:read", "events:write"])]
    #[AssertBPM\Identifier(['entityClass' => EventType::class])]
    public ?int $eventType;

    #[Groups(["events:read", "events:write"])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'int')]
    #[Validator\NoCollapse]
    public ?int $slotsLimit;

    #[Groups(["events:read", "events:write"])]
    #[Assert\Type(type: 'bool')]
    public bool $isLocked = false;

    #[Groups(["events:read", "events:write"])]
    #[AssertBPM\Identifier(['entityClass' => Faction::class])]
    public ?int $faction;

    #[Groups(["events:read", "events:write"])]
    #[AssertBPM\Identifier(['entityClass' => Region::class])]
    public ?int $region;

    #[Groups(["events:read", "events:write"])]
    #[AssertBPM\Identifier(['entityClass' => Game::class])]
    public ?int $game;
}
