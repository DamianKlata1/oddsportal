<?php

namespace App\Controller\Api;

use App\DTO\User\UserEditDTO;
use App\Service\Interface\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProfileController extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }

    #[Route('/profile', name: 'api_profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        return $this->json($this->getUser(), Response::HTTP_OK, [], ['groups' => 'getUser']);
    }

    #[Route('/profile', name: 'api_profile_edit', methods: ['PATCH'])]
    public function editProfile(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] UserEditDTO $userEditDTO
    ): JsonResponse {
        $this->userService->editUser($this->getUser(), $userEditDTO);

        return $this->json(['message' => 'User edited successfully.'], status: Response::HTTP_OK);
    }
    
}
