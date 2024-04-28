<?php 
// src/Validator/Constraints/UniqueSchoolNameValidator.php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

class UniqueSchoolNameValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $repository = $this->entityManager->getRepository('App\Entity\Ecole');
        $school = $repository->findOneBy(['nom' => $value]);

        if ($school) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
