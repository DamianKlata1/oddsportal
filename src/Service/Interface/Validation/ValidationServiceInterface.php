<?php

namespace App\Service\Interface\Validation;

interface ValidationServiceInterface
{
    public function validate(object $object): void;
    public function validateAll(array $objects): void;

}