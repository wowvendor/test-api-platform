<?php

namespace App\DataTransformer\Events;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\Events\EventOutput;
use App\Entity\Events\Event;
use JetBrains\PhpStorm\Pure;

class EventOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    #[Pure] public function transform($object, string $to, array $context = []): EventOutput
    {
        $output = new EventOutput();
        $output->id = $object->getId();
        $output->datetime = $object->getDatetime()->getTimestamp();
        $output->booster = $object->getBooster();
        $output->eventType = $object->getEventType();
        $output->slotsLimit = [
            'total' => $object->getSlotsLimit(),
            'reserved' => 0,
        ];
        $output->isLocked = $object->getIsLocked();
        $output->faction = $object->getFaction();
        $output->region = $object->getRegion();
        $output->game = $object->getGame();

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return EventOutput::class === $to && $data instanceof Event;
    }
}
