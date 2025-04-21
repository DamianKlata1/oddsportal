<?php

namespace App\Controller;

use App\DTO\UserEditDTO;
use App\Entity\User;
use App\Service\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class ProfileController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface   $serializer,
        private readonly UserServiceInterface  $userService,
        private readonly TokenStorageInterface $tokenStorage
    )
    {
    }

    #[Route('/api/account', name: 'api_account', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): JsonResponse
    {
        $json = $this->serializer->serialize($this->tokenStorage->getToken()->getUser(), 'json', ['groups' => 'getUser']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/account/edit', name: 'api_account_edit', methods: ['PATCH'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function editUser(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY,)] UserEditDTO $userEditDTO
    ): JsonResponse
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $this->userService->editUser($user, $userEditDTO);

        return new JsonResponse(['message' => 'User edited successfully.'], Response::HTTP_OK, [], true);
    }
}
