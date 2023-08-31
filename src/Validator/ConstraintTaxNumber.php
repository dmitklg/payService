<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class ConstraintTaxNumber extends Constraint
{
    public string $message = 'Invalid Tax Number.';
}
