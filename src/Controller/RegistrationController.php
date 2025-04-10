<?php

namespace App\Controller;

use App\DTO\NewUserDTO;
use App\Repository\UserRepositoryInterface;
use App\Service\Interface\EmailVerifierServiceInterface;
use App\Service\Interface\RegistrationEmailBuilderInterface;
use App\Service\Interface\UserServiceInterface;
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
    public function __construct(private readonly EmailVerifierServiceInterface     $emailVerifier,
                                private readonly UserServiceInterface              $userService,
                                private readonly RegistrationEmailBuilderInterface $registrationEmailBuilder,
                                private readonly TranslatorInterface               $translator,
                                private readonly UserRepositoryInterface           $userRepository)
    {
    }

    /**
     */
    #[Route('/api/register', name: 'api_register',methods: ['POST'])]
    public function registerUser(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY,)] NewUserDTO $userDTO
    ): JsonResponse
    {
        $user = $this->userService->registerUser($userDTO);

        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            $this->registrationEmailBuilder->buildConfirmationEmail($user));

        return $this->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
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
