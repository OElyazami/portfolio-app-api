<?php

namespace App\Controller\Api;

use App\Dto\Category\Input\CreateCategoryDto;
use App\Dto\Category\Input\UpdateCategoryDto;
use App\Entity\Category;
use App\Response\ErrorResponse;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('category', name: 'category_')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryService $categoryService,
        private EntityManagerInterface $em
    ) {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $response = $this->categoryService->getCategories();
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/active', name: 'active', methods: ['GET'])]
    public function active(): JsonResponse
    {
        $response = $this->categoryService->getActiveCategories();
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $response = $this->categoryService->getCategory($id);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateCategoryDto $dto
    ): JsonResponse {
        $response = $this->categoryService->createCategory($dto);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateCategoryDto $dto
    ): JsonResponse {
        $category = $this->findCategoryOrFail($id);
        if ($category instanceof JsonResponse) {
            return $category;
        }

        $response = $this->categoryService->updateCategory($dto, $category);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->findCategoryOrFail($id);
        if ($category instanceof JsonResponse) {
            return $category;
        }

        $response = $this->categoryService->deleteCategory($category);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Find a category or return an error JsonResponse
     */
    private function findCategoryOrFail(int $id): Category|JsonResponse
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('Category not found')
                ->setCode(Response::HTTP_NOT_FOUND);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        return $category;
    }
}
