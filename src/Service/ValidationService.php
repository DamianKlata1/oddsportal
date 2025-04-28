<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Service\Interface\ValidationServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService implements ValidationServiceInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function validate(object $object): void
    {
        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            $errorMessage = "";
            foreach ($errors as $error) {
                $errorMessage .= $error->getMessage() . "\n";
            }
            throw new ValidationException(
                $errorMessage,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
    public function validateAll(array $objects): void
    {
        foreach ($objects as $object) {
            $this->validate($object);
        }
    }
}