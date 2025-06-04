<?php

namespace App\Controller\Api;

use App\DTO\User\UserEditDTO;
use App\Entity\User;
use App\Service\Interface\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProfileController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly UserServiceInterface $userService,
    ) {
    }

    #[Route('/account', name: 'api_account', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $json = $this->serializer->serialize($this->getUser(), 'json', ['groups' => 'getUser']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/account/edit', name: 'api_account_edit', methods: ['PATCH'])]
    public function editUser(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY, )] UserEditDTO $userEditDTO
    ): JsonResponse {
        $this->userService->editUser($this->getUser(), $userEditDTO);

        return $this->json(['message' => 'User edited successfully.'], Response::HTTP_OK);
    }
}
