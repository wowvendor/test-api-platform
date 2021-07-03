<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ExistEntity extends Constraint
{
    public string $message = 'There is no such resource "{{ entityClass }}" with id "{{ value }}".';
    public string $entityClass;

    public function __construct(
        string $entityClass,
        array $options = null,
        string $message = null,
        array $groups = null
    ) {
        parent::__construct($options ?? [], $groups);

        $this->entityClass = $entityClass;
        $this->message = $message ?? $this->message;
    }
}
