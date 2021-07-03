<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\CollectionValidator;

class ObjectiveValidator extends CollectionValidator
{

    public function validate($value, Constraint $constraint)
    {
        $fields = $this->getFields($value);
        parent::validate($fields, $constraint);
    }

    private function getFields(mixed $object) : array {
        if (is_array($object)) {
            return $object;
        }

        if (get_class($object) === "stdClass") {
            return get_object_vars($object);
        }

        $reflect = new \ReflectionClass($object);
        $properties = $reflect->getProperties();
        $fields = [];
        $className = get_class($object);
        foreach ($properties as $property) {
            if ($property->isProtected() || $property->isPrivate()) {
                $property->setAccessible(true);
            }
            $fields[$property->getName()] = $property->getValue(new $className);
            $property->setAccessible(false);
        }
        return $fields;
    }
}
