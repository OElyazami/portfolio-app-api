<?php

namespace App\Service;

use App\Dto\Category\Input\CreateCategoryDto;
use App\Dto\Category\Input\UpdateCategoryDto;
use App\Dto\Category\Output\CategoryOutputDto;
use App\Entity\Category;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    public function __construct(
        private EntityManagerInterface $em,
        private SluggerService $slugger
    ) {}

    /**
     * Get all categories
     */
    public function getCategories(): IResponseArrayFormat
    {
        try {
            $categories = $this->em->getRepository(Category::class)
                ->findBy([], ['name' => 'ASC']);

            $data = array_map(
                fn(Category $cat) => CategoryOutputDto::fromEntity($cat)->toArray(),
                $categories
            );

            return (new SuccessResponse())->setData($data);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get active categories only
     */
    public function getActiveCategories(): IResponseArrayFormat
    {
        try {
            $categories = $this->em->getRepository(Category::class)
                ->findBy(['isActive' => true], ['name' => 'ASC']);

            $data = array_map(
                fn(Category $cat) => CategoryOutputDto::fromEntity($cat)->toArray(),
                $categories
            );

            return (new SuccessResponse())->setData($data);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get a single category by ID
     */
    public function getCategory(int $id): IResponseArrayFormat
    {
        try {
            $category = $this->em->getRepository(Category::class)->find($id);

            if (!$category) {
                return (new ErrorResponse())
                    ->setMessage('Category not found')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            return (new SuccessResponse())->setData(CategoryOutputDto::fromEntity($category)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Create a new category
     */
    public function createCategory(CreateCategoryDto $dto): IResponseArrayFormat
    {
        try {
            // Check for duplicate name
            $existing = $this->em->getRepository(Category::class)
                ->findOneBy(['name' => $dto->name]);

            if ($existing) {
                return (new ErrorResponse())
                    ->setMessage('A category with this name already exists')
                    ->setCode(Response::HTTP_CONFLICT);
            }

            $category = new Category();
            $category->setName($dto->name)
                     ->setSlug($dto->slug ?? $this->slugger->slugify($dto->name))
                     ->setDescription($dto->description)
                     ->setColor($dto->color)
                     ->setIcon($dto->icon)
                     ->setIsActive($dto->isActive);

            $this->em->persist($category);
            $this->em->flush();

            return (new SuccessResponse())
                ->setData(CategoryOutputDto::fromEntity($category)->toArray())
                ->setCode(Response::HTTP_CREATED);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Update an existing category
     */
    public function updateCategory(UpdateCategoryDto $dto, Category $category): IResponseArrayFormat
    {
        try {
            if ($dto->name !== null) {
                // Check for duplicate name (excluding current)
                $existing = $this->em->getRepository(Category::class)
                    ->findOneBy(['name' => $dto->name]);

                if ($existing && $existing->getId() !== $category->getId()) {
                    return (new ErrorResponse())
                        ->setMessage('A category with this name already exists')
                        ->setCode(Response::HTTP_CONFLICT);
                }

                $category->setName($dto->name);

                if ($dto->slug === null) {
                    $category->setSlug($this->slugger->slugify($dto->name));
                }
            }

            if ($dto->slug !== null) {
                $category->setSlug($dto->slug);
            }

            if ($dto->description !== null) {
                $category->setDescription($dto->description);
            }

            if ($dto->color !== null) {
                $category->setColor($dto->color);
            }

            if ($dto->icon !== null) {
                $category->setIcon($dto->icon);
            }

            if ($dto->isActive !== null) {
                $category->setIsActive($dto->isActive);
            }

            $this->em->flush();

            return (new SuccessResponse())
                ->setData(CategoryOutputDto::fromEntity($category)->toArray())
                ->setMessage('Category updated successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Delete a category
     */
    public function deleteCategory(Category $category): IResponseArrayFormat
    {
        try {
            if ($category->getProjects()->count() > 0) {
                return (new ErrorResponse())
                    ->setMessage('Cannot delete category that has projects assigned')
                    ->setCode(Response::HTTP_CONFLICT);
            }

            $this->em->remove($category);
            $this->em->flush();

            return (new SuccessResponse())->setMessage('Category deleted successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }
}
