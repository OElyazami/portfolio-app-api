<?php

namespace App\Service;

use App\Dto\Project\Input\CreateProjectDto;
use App\Dto\Project\Input\UpdateProjectDto;
use App\Dto\Project\Output\ProjectOutputDto;
use App\Dto\Project\Output\ProjectListDto;
use App\Entity\Project;
use App\Entity\Category;
use App\Entity\Skill;
use App\Entity\Company;
use App\Entity\Client;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectService
{
    public function __construct(
        private EntityManagerInterface $em,
        private SluggerService $slugger
    ) {}

    public function getProject(int $id): IResponseArrayFormat
    {
        try {
            $project = $this->em->getRepository(Project::class)->find($id);
            
            if (!$project) {
                return (new ErrorResponse())->setMessage('PROJECT-NOT-FOUND')->setCode(Response::HTTP_NOT_FOUND);
            }
            
            return (new SuccessResponse())->setData(ProjectOutputDto::fromEntity($project)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get all projects with pagination/filtering
     */
    public function getProjects(Request $request): IResponseArrayFormat
    {
        try {
            $page = $request->query->getInt('page', 1);
            $limit = $request->query->getInt('limit', 10);
            $isFeatured = $request->query->getBoolean('featured', false);
            $categoryId = $request->query->getInt('category');
            $skillId = $request->query->getInt('skill');
            $userId = $request->query->getInt('user');
            $search = $request->query->get('search');
            
            $repository = $this->em->getRepository(Project::class);
            
            // Build query criteria
            $criteria = [];
            if ($isFeatured) {
                $criteria['isFeatured'] = true;
            }
            if ($userId) {
                $criteria['user'] = $userId;
            }
            
            // Get paginated results
            $paginator = $repository->findByCriteria(
                $criteria,
                $categoryId,
                $skillId,
                $search,
                $page,
                $limit
            );
            
            $totalItems = $paginator->count();
            $totalPages = ceil($totalItems / $limit);
            
            $projects = [];
            foreach ($paginator as $project) {
                $projects[] = ProjectOutputDto::fromEntity($project)->toArray();
            }
            
            $responseData = [
                'items' => $projects,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_items' => $totalItems,
                    'items_per_page' => $limit,
                    'has_next_page' => $page < $totalPages,
                    'has_previous_page' => $page > 1,
                ]
            ];
            
            return (new SuccessResponse())->setData($responseData);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Create a new project from DTO
     */
    public function createProject(CreateProjectDto $dto): IResponseArrayFormat
    {
        try {
            // Find user
            $user = $this->em->getRepository(User::class)->find($dto->userId);
            if (!$user) {
                return (new ErrorResponse())->setMessage('User not found');
            }
            
            // Create project
            $project = new Project();
            $project->setTitle($dto->title)
                    ->setSlug($dto->slug ?? $this->generateSlug($dto->title))
                    ->setDescription($dto->description)
                    ->setShortDescription($dto->shortDescription)
                    ->setFeaturedImage($dto->featuredImage)
                    ->setYears($dto->years)
                    ->setIsFeatured($dto->isFeatured)
                    ->setUser($user);
            
            // Handle company
            if ($dto->companyId) {
                $company = $this->em->getRepository(Company::class)->find($dto->companyId);
                if ($company) {
                    $project->setCompany($company);
                }
            }
            
            // Handle client
            if ($dto->clientId) {
                $client = $this->em->getRepository(Client::class)->find($dto->clientId);
                if ($client) {
                    $project->setClient($client);
                }
            }
            
            // Handle categories
            foreach ($dto->categories as $categoryId) {
                $category = $this->em->getRepository(Category::class)->find($categoryId);
                if ($category) {
                    $project->addCategory($category);
                }
            }
            
            // Handle skills
            foreach ($dto->skills as $skillId) {
                $skill = $this->em->getRepository(Skill::class)->find($skillId);
                if ($skill) {
                    $project->addSkill($skill);
                }
            }
            
            $this->em->persist($project);
            $this->em->flush();
            
            return (new SuccessResponse())
                ->setData(ProjectOutputDto::fromEntity($project)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Update an existing project from DTO
     */
    public function updateProject(UpdateProjectDto $dto, Project $project): IResponseArrayFormat
    {
        try {
            // Update basic fields if provided
            if ($dto->title !== null) {
                $project->setTitle($dto->title);
                if ($dto->slug === null) {
                    $project->setSlug($this->generateSlug($dto->title));
                }
            }
            
            if ($dto->slug !== null) {
                $project->setSlug($dto->slug);
            }
            
            if ($dto->description !== null) {
                $project->setDescription($dto->description);
            }
            
            if ($dto->shortDescription !== null) {
                $project->setShortDescription($dto->shortDescription);
            }
            
            if ($dto->featuredImage !== null) {
                $project->setFeaturedImage($dto->featuredImage);
            }
            
            if ($dto->years !== null) {
                $project->setYears($dto->years);
            }
            
            if ($dto->isFeatured !== null) {
                $project->setIsFeatured($dto->isFeatured);
            }
            
            // Handle company
            if ($dto->companyId !== null) {
                if ($dto->companyId === 0) {
                    $project->setCompany(null);
                } else {
                    $company = $this->em->getRepository(Company::class)->find($dto->companyId);
                    if ($company) {
                        $project->setCompany($company);
                    }
                }
            }
            
            // Handle client
            if ($dto->clientId !== null) {
                if ($dto->clientId === 0) {
                    $project->setClient(null);
                } else {
                    $client = $this->em->getRepository(Client::class)->find($dto->clientId);
                    if ($client) {
                        $project->setClient($client);
                    }
                }
            }
            
            // Handle categories (replace existing)
            if ($dto->categories !== null) {
                // Clear existing categories
                foreach ($project->getCategories() as $category) {
                    $project->removeCategory($category);
                }
                
                // Add new categories
                foreach ($dto->categories as $categoryId) {
                    $category = $this->em->getRepository(Category::class)->find($categoryId);
                    if ($category) {
                        $project->addCategory($category);
                    }
                }
            }
            
            // Handle skills (replace existing)
            if ($dto->skills !== null) {
                // Clear existing skills
                foreach ($project->getSkills() as $skill) {
                    $project->removeSkill($skill);
                }
                
                // Add new skills
                foreach ($dto->skills as $skillId) {
                    $skill = $this->em->getRepository(Skill::class)->find($skillId);
                    if ($skill) {
                        $project->addSkill($skill);
                    }
                }
            }
            
            $this->em->flush();
            
            return (new SuccessResponse())
                ->setData(ProjectOutputDto::fromEntity($project)->toArray())
                ->setMessage('Project updated successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Delete a project
     */
    public function deleteProject(Project $project): IResponseArrayFormat
    {
        try {
            $this->em->remove($project);
            $this->em->flush();
            
            return (new SuccessResponse())->setMessage('Project deleted successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Project $project): IResponseArrayFormat
    {
        try {
            $project->setIsFeatured(!$project->getIsFeatured());
            $this->em->flush();
            
            $status = $project->getIsFeatured() ? 'featured' : 'unfeatured';
            
            return (new SuccessResponse())
                ->setData(['isFeatured' => $project->getIsFeatured()])
                ->setMessage("Project {$status} successfully");
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get featured projects
     */
    public function getFeaturedProjects(int $limit = 6): IResponseArrayFormat
    {
        try {
            $projects = $this->em->getRepository(Project::class)
                ->findBy(['isFeatured' => true], ['years' => 'DESC'], $limit);
            
            $projectsData = array_map(
                fn($project) => ProjectOutputDto::fromEntity($project)->toArray(),
                $projects
            );
            
            return (new SuccessResponse())->setData($projectsData);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get projects by user
     */
    public function getProjectsByUser(User $user): IResponseArrayFormat
    {
        try {
            $projects = $this->em->getRepository(Project::class)
                ->findBy(['user' => $user], ['years' => 'DESC']);
            
            $projectsData = array_map(
                fn($project) => ProjectOutputDto::fromEntity($project)->toArray(),
                $projects
            );
            
            return (new SuccessResponse())->setData($projectsData);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get projects by category
     */
    public function getProjectsByCategory(Category $category): IResponseArrayFormat
    {
        try {
            $projects = $category->getProjects();
            
            $projectsData = array_map(
                fn($project) => ProjectOutputDto::fromEntity($project)->toArray(),
                $projects->toArray()
            );
            
            return (new SuccessResponse())->setData($projectsData);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get projects by skill
     */
    public function getProjectsBySkill(Skill $skill): IResponseArrayFormat
    {
        try {
            $projects = $skill->getProjects();
            
            $projectsData = array_map(
                fn($project) => ProjectOutputDto::fromEntity($project)->toArray(),
                $projects->toArray()
            );
            
            return (new SuccessResponse())->setData($projectsData);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Search projects
     */
    public function searchProjects(string $query, int $limit = 10): IResponseArrayFormat
    {
        try {
            $repository = $this->em->getRepository(Project::class);
            $projects = $repository->search($query, $limit);
            
            $projectsData = array_map(
                fn($project) => ProjectOutputDto::fromEntity($project)->toArray(),
                $projects
            );
            
            return (new SuccessResponse())->setData([
                'query' => $query,
                'results' => $projectsData,
                'count' => count($projectsData)
            ]);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get project statistics
     */
    public function getProjectStatistics(): IResponseArrayFormat
    {
        try {
            $repository = $this->em->getRepository(Project::class);
            
            $stats = [
                'total_projects' => $repository->count([]),
                'featured_projects' => $repository->count(['isFeatured' => true]),
                'average_years' => $repository->getAverageYears(),
                'projects_by_year' => $repository->getProjectsByYear(),
                'top_categories' => $repository->getTopCategories(5),
                'top_skills' => $repository->getTopSkills(5),
            ];
            
            return (new SuccessResponse())->setData($stats);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Bulk update projects (e.g., update categories for multiple projects)
     */
    public function bulkUpdate(array $projectIds, array $data): IResponseArrayFormat
    {
        try {
            $updatedCount = 0;
            $repository = $this->em->getRepository(Project::class);
            
            foreach ($projectIds as $projectId) {
                $project = $repository->find($projectId);
                if ($project) {
                    // Update based on provided data
                    if (isset($data['isFeatured'])) {
                        $project->setIsFeatured($data['isFeatured']);
                    }
                    if (isset($data['years'])) {
                        $project->setYears($data['years']);
                    }
                    
                    $updatedCount++;
                }
            }
            
            $this->em->flush();
            
            return (new SuccessResponse())
                ->setData(['updated_count' => $updatedCount])
                ->setMessage("{$updatedCount} projects updated successfully");
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Generate a unique slug for the project
     */
    private function generateSlug(string $title): string
    {
        // If you have a SluggerService, use it:
        if (class_exists(SluggerService::class)) {
            return $this->slugger->slugify($title);
        }
        
        // Simple slug generation
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        
        // Check if slug exists
        $counter = 1;
        $originalSlug = $slug;
        
        while ($this->em->getRepository(Project::class)->findOneBy(['slug' => $slug])) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}