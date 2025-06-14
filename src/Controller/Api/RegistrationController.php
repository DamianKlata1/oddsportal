<?php

namespace App\Controller\Api;

use App\DTO\User\NewUserDTO;
use App\Repository\Interface\UserRepositoryInterface;
use App\Service\Interface\Email\EmailVerifierServiceInterface;
use App\Service\Interface\Email\RegistrationEmailBuilderInterface;
use App\Service\Interface\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly EmailVerifierServiceInterface $emailVerifier,
        private readonly UserServiceInterface $userService,
        private readonly RegistrationEmailBuilderInterface $registrationEmailBuilder,
        private readonly TranslatorInterface $translator,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }
    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function registerUser(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] NewUserDTO $userDTO
    ): JsonResponse {
        $user = $this->userService->registerUser($userDTO);

        $this->emailVerifier->sendEmailConfirmation(
            'api_verify_email',
            $user,
            $this->registrationEmailBuilder->buildConfirmationEmail($user)
        );

        return $this->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }

    #[Route('/verify/email', name: 'api_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_home', ['path' => '']);
        }

        $user = $this->userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_home', ['path' => '']);
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request->getUri(), $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $this->translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_home', ['path' => '']);
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_home', ['path' => '']);
    }
}
