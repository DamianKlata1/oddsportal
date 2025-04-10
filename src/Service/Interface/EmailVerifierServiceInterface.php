<?php
namespace App\Service\Interface;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\User\UserInterface;

interface EmailVerifierServiceInterface
{
    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void;
    public function handleEmailConfirmation(string $signedUrl, UserInterface $user): void;

}