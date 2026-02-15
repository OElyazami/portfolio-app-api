<?php

namespace App\Controller\Api;

use App\Dto\Project\Input\CreateProjectDto;
use App\Dto\Project\Input\UpdateProjectDto;
use App\Entity\Project;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('project', name: 'project_')]
class ProjectController extends AbstractController
{
    public function __construct(
        private ProjectService $projectService,
        private EntityManagerInterface $em
    ) {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $response = $this->projectService->getProjects($request);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/featured', name: 'featured', methods: ['GET'])]
    public function featured(Request $request): JsonResponse
    {
        $limit = $request->query->getInt('limit', 6);
        $response = $this->projectService->getFeaturedProjects($limit);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->getInt('limit', 10);

        if (strlen($query) < 2) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('Search query must be at least 2 characters')
                ->setCode(Response::HTTP_BAD_REQUEST);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        $response = $this->projectService->searchProjects($query, $limit);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/statistics', name: 'statistics', methods: ['GET'])]
    public function statistics(): JsonResponse
    {
        $response = $this->projectService->getProjectStatistics();
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/my-projects', name: 'my_projects', methods: ['GET'])]
    public function myProjects(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->projectService->getProjectsByUser($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $response = $this->projectService->getProject($id);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateProjectDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $response = $this->projectService->createProject($dto, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateProjectDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $project = $this->findProjectOrFail($id);
        if ($project instanceof JsonResponse) {
            return $project;
        }

        // Verify ownership
        if ($project->getUser()->getId() !== $user->getId()) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('You do not own this project')
                ->setCode(Response::HTTP_FORBIDDEN);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        $response = $this->projectService->updateProject($dto, $project);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
        #[CurrentUser] User $user
    ): JsonResponse {
        $project = $this->findProjectOrFail($id);
        if ($project instanceof JsonResponse) {
            return $project;
        }

        // Verify ownership
        if ($project->getUser()->getId() !== $user->getId()) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('You do not own this project')
                ->setCode(Response::HTTP_FORBIDDEN);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        $response = $this->projectService->deleteProject($project);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}/toggle-featured', name: 'toggle_featured', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function toggleFeatured(
        int $id,
        #[CurrentUser] User $user
    ): JsonResponse {
        $project = $this->findProjectOrFail($id);
        if ($project instanceof JsonResponse) {
            return $project;
        }

        // Verify ownership
        if ($project->getUser()->getId() !== $user->getId()) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('You do not own this project')
                ->setCode(Response::HTTP_FORBIDDEN);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        $response = $this->projectService->toggleFeatured($project);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Find a project or return an error JsonResponse
     */
    private function findProjectOrFail(int $id): Project|JsonResponse
    {
        $project = $this->em->getRepository(Project::class)->find($id);

        if (!$project) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('Project not found')
                ->setCode(Response::HTTP_NOT_FOUND);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        return $project;
    }
}