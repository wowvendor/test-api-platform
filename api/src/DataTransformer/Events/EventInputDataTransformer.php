<?php

namespace App\DataTransformer\Events;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\Events\EventInput;
use App\Entity\Events\Booster;
use App\Entity\Events\Event;
use App\Entity\Events\EventType;
use App\Entity\Events\Faction;
use App\Entity\Events\Region;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class EventInputDataTransformer implements DataTransformerInterface
{
    private EntityManagerInterface $entityManager;
    private SymfonyValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, SymfonyValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param EventInput $object
     * @param string     $to
     * @param array      $context
     *
     * @return Event
     */
    public function transform($object, string $to, array $context = []): Event
    {
        $this->validator->validate($object);

        $event = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? new Event();

        $datetime = new \DateTime();
        $datetime->setTimestamp($object->datetime);
        $event->setDatetime($datetime);

        $event->setSlotsLimit($object->slotsLimit);
        $event->setIsLocked($object->isLocked);

        /** @var Booster $booster */
        $booster = $this->entityManager->getRepository(Booster::class)->find($object->booster);
        $event->setBooster($booster);
        /** @var EventType $eventType */
        $eventType = $this->entityManager->getRepository(EventType::class)->find($object->eventType);
        $event->setEventType($eventType);
        /** @var Faction $faction */
        $faction = $this->entityManager->getRepository(Faction::class)->find($object->faction);
        $event->setFaction($faction);
        /** @var Region $region */
        $region = $this->entityManager->getRepository(Region::class)->find($object->region);
        $event->setRegion($region);
        /** @var Game $game */
        $game = $this->entityManager->getRepository(Game::class)->find($object->game);
        $event->setGame($game);

        return $event;
    }

    private function transformArray(array $array) : array
    {
        $newArray = [];
        foreach ($array as $elem) {
            $newArray[] = (object) $elem;
        }
        return $newArray;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Event) {
            return false;
        }

        return $to === Event::class && ($context['input']['class'] ?? null) === EventInput::class;
    }

}
