<?php

namespace App\Service\Interface;

interface ValidationServiceInterface
{
    public function validate(object $object): void;
    public function validateAll(array $objects): void;

}