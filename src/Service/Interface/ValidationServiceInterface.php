<?php

namespace App\Service\Interface;

interface ValidationServiceInterface
{
    public function validate(object $object): void;

}