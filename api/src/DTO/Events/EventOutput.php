<?php

namespace App\DTO\Events;

use App\Entity\Events\Booster;
use App\Entity\Events\EventType;
use App\Entity\Events\Faction;
use App\Entity\Events\Region;
use App\Entity\Game;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

final class EventOutput
{
    #[Groups(["events:read", "mythicKeyLogs:read"])]
    public int $id;

    #[Groups(["events:read", "events:write"])]
    public int $datetime;

    #[Groups(["events:read", "events:write"])]
    public Booster $booster;

    #[Groups(["events:read", "events:write"])]
    public EventType $eventType;

    #[Groups(["events:read", "events:write"])]
    public array $slotsLimit;

    #[Groups(["events:read", "events:write"])]
    public bool $isLocked;

    #[Groups(["events:read", "events:write"])]
    public Faction $faction;

    #[Groups(["events:read", "events:write"])]
    public Region $region;

    #[Groups(["events:read", "events:write"])]
    public Game $game;
}
