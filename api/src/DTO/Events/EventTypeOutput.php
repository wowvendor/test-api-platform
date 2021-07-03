<?php


namespace App\DTO\Events;


use App\Entity\Game;
use Symfony\Component\Serializer\Annotation\Groups;

class EventTypeOutput
{
    #[Groups(["eventTypes:read", "eventAttributes"])]
    public int $id;

    #[Groups(["eventTypes:read", "events:read", "eventAttributes"])]
    public string $name;

    #[Groups(["eventTypes:read"])]
    public bool $isActive = true;

    #[Groups(["eventTypes:read", "eventAttributes"])]
    public ?string $lootLimit;

    #[Groups(["eventTypes:read"])]
    public Game $game;
}
