<?php

namespace App\Serializer\Normalizer\Events;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\Events\EventInput;
use App\Entity\Events\Event;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EventInputDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    private ObjectNormalizer $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] = $this->createDto($context);

        return $this->objectNormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === EventInput::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    private function createDto(array $context): EventInput
    {
        $entity = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;

        $dto = new EventInput();

        if (!$entity) {
            $dto->isLocked = false;
            return $dto;
        }

        if (!$entity instanceof Event) {
            throw new \Exception(sprintf('Unexpected resource class %s', get_class($entity)));
        }

        $dto->datetime = $entity->getDatetime()->getTimestamp();
        $dto->booster = $entity->getBooster()->getId();
        $dto->eventType = $entity->getEventType()->getId();
        $dto->slotsLimit = $entity->getSlotsLimit();
        $dto->isLocked = $entity->getIsLocked();
        $dto->faction = $entity->getFaction()->getId();
        $dto->region = $entity->getRegion()->getId();
        $dto->game = $entity->getGame()->getId();

        return $dto;
    }
}
