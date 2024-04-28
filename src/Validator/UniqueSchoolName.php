<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueSchoolName extends Constraint
{
    public $message = 'Ce nom d\'école est déjà utilisé.';
}

