<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class NoCollapse extends Modifier
{
    public function execute(mixed $value = null) : bool
    {
        return true;
    }
}
