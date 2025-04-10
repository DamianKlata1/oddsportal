<?php

namespace App\Service;

use App\Service\Interface\RegistrationEmailBuilderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationEmailBuilder implements RegistrationEmailBuilderInterface
{
    public function buildConfirmationEmail(UserInterface $user): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address('no-replay@oddsportal.com', 'oddsportal'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');
    }
}
