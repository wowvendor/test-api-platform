<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Identifier
 * Checks identifier
 *
 * Available options, passed by assoc array:
 *      required: true|false (default true, check the field for existence and null)
 *      idType: int|integer|string (default int, in case of int or integer valid
 *                                                  only positive values)
 *      entityClass: string (default null, name of specific repository, if passed it
*                               will check for existence entity with a current id)
 *
 * @package App\Validator\Constraints
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Identifier extends Compound
{
    public bool $required;
    public string $idType;
    public ?string $entityClass;

    protected function getConstraints(array $options): array
    {
        $this->required = $options['required'] ?? true;
        $this->idType = $options['idType'] ?? 'int';
        $this->entityClass = $options['entityClass'] ?? null;

        $asserts[] = new Assert\NotBlank(['allowNull' => !$this->required]);
        if ($this->idType === 'int' || $this->idType === 'integer') {
            $asserts[] = new Assert\Type($this->idType);
            $asserts[] = new Assert\Positive();
        } elseif ($this->idType === 'string') {
            $asserts[] = new Assert\Type($this->idType);
        }
        if ($this->entityClass !== null) {
            $asserts[] = new ExistEntity($this->entityClass);
        }

        return $asserts;
    }
}
