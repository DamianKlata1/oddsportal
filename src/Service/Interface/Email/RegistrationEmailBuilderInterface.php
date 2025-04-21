<?php

namespace App\Service\Interface\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\User\UserInterface;

interface RegistrationEmailBuilderInterface
{
    public function buildConfirmationEmail(UserInterface $user): TemplatedEmail;


}