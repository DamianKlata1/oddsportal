<?php

namespace App\Validator;

use App\Repository\Interface\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\UniqueUserEmail $constraint */

        if (null === $value || '' === $value || !$constraint instanceof UniqueUserEmail) {
            return;
        }

        if ($this->userRepository->findOneBy(['email' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
