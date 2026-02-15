<?php

namespace App\Controller\Api;

use App\Dto\Skill\Input\CreateSkillDto;
use App\Dto\Skill\Input\UpdateSkillDto;
use App\Entity\Skill;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Service\SkillService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('skill', name: 'skill_')]
class SkillController extends AbstractController
{
    public function __construct(
        private SkillService $skillService,
        private EntityManagerInterface $em
    ) {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->skillService->getSkillsByUser($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/featured', name: 'featured', methods: ['GET'])]
    public function featured(Request $request): JsonResponse
    {
        $limit = $request->query->getInt('limit', 0) ?: null;
        $response = $this->skillService->getFeaturedSkills($limit);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $response = $this->skillService->getSkill($id);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateSkillDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $response = $this->skillService->createSkill($dto, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateSkillDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $skill = $this->findSkillOrFail($id);
        if ($skill instanceof JsonResponse) {
            return $skill;
        }

        $response = $this->skillService->updateSkill($dto, $skill, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
        #[CurrentUser] User $user
    ): JsonResponse {
        $skill = $this->findSkillOrFail($id);
        if ($skill instanceof JsonResponse) {
            return $skill;
        }

        $response = $this->skillService->deleteSkill($skill, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Find a skill or return an error JsonResponse
     */
    private function findSkillOrFail(int $id): Skill|JsonResponse
    {
        $skill = $this->em->getRepository(Skill::class)->find($id);

        if (!$skill) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('Skill not found')
                ->setCode(Response::HTTP_NOT_FOUND);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        return $skill;
    }
}