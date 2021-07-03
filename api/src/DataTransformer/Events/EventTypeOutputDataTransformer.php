<?php

namespace App\DataTransformer\Events;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\Events\EventTypeOutput;
use App\Entity\Events\EventType;

class EventTypeOutputDataTransformer implements DataTransformerInterface
{

    /**
     * {@inheritdoc}
     */
    public function transform($object, string $to, array $context = []): EventTypeOutput
    {
        $output = new EventTypeOutput();
        $output->id = $object->getId();
        $output->name = $object->getName();
        $output->isActive = $object->getIsActive();
        $output->game = $object->getGame();
        $output->lootLimit = is_null($object->getLootLimit()) ? null : $object->getLootLimit()->getType();

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return EventTypeOutput::class === $to && $data instanceof EventType;
    }
}
