<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class Objective extends Collection
{
    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
}
