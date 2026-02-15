<?php

namespace App\Service;

use App\Dto\Skill\Input\CreateSkillDto;
use App\Dto\Skill\Input\UpdateSkillDto;
use App\Dto\Skill\Output\SkillOutputDto;
use App\Entity\Skill;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class SkillService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Get all skills for a user
     */
    public function getSkillsByUser(User $user): IResponseArrayFormat
    {
        try {
            $skills = $this->em->getRepository(Skill::class)
                ->findBy(['user' => $user], ['name' => 'ASC']);

            $skillsData = array_map(
                fn(Skill $skill) => SkillOutputDto::fromEntity($skill)->toArray(),
                $skills
            );

            return (new SuccessResponse())->setData($skillsData);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get a single skill by ID
     */
    public function getSkill(int $id): IResponseArrayFormat
    {
        try {
            $skill = $this->em->getRepository(Skill::class)->find($id);

            if (!$skill) {
                return (new ErrorResponse())
                    ->setMessage('Skill not found')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            return (new SuccessResponse())->setData(SkillOutputDto::fromEntity($skill)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get featured skills
     */
    public function getFeaturedSkills(?int $limit = null): IResponseArrayFormat
    {
        try {
            $skills = $this->em->getRepository(Skill::class)
                ->findBy(
                    ['isFeatured' => true],
                    ['yearsOfExperience' => 'DESC'],
                    $limit
                );

            $skillsData = array_map(
                fn(Skill $skill) => SkillOutputDto::fromEntity($skill)->toArray(),
                $skills
            );

            return (new SuccessResponse())->setData($skillsData);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Create a new skill
     */
    public function createSkill(CreateSkillDto $dto, User $user): IResponseArrayFormat
    {
        try {
            // Check for duplicate skill name for this user
            $existing = $this->em->getRepository(Skill::class)
                ->findOneBy(['name' => $dto->name, 'user' => $user]);

            if ($existing) {
                return (new ErrorResponse())
                    ->setMessage('A skill with this name already exists')
                    ->setCode(Response::HTTP_CONFLICT);
            }

            $skill = new Skill();
            $skill->setName($dto->name)
                  ->setIcon($dto->icon)
                  ->setYearsOfExperience($dto->yearsOfExperience)
                  ->setIsFeatured($dto->isFeatured)
                  ->setUser($user);

            $this->em->persist($skill);
            $this->em->flush();

            return (new SuccessResponse())
                ->setData(SkillOutputDto::fromEntity($skill)->toArray())
                ->setCode(Response::HTTP_CREATED);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Update an existing skill
     */
    public function updateSkill(UpdateSkillDto $dto, Skill $skill, User $user): IResponseArrayFormat
    {
        try {
            // Verify ownership
            if ($skill->getUser()?->getId() !== $user->getId()) {
                return (new ErrorResponse())
                    ->setMessage('You do not own this skill')
                    ->setCode(Response::HTTP_FORBIDDEN);
            }

            if ($dto->name !== null) {
                // Check for duplicate name (excluding current skill)
                $existing = $this->em->getRepository(Skill::class)
                    ->findOneBy(['name' => $dto->name, 'user' => $user]);

                if ($existing && $existing->getId() !== $skill->getId()) {
                    return (new ErrorResponse())
                        ->setMessage('A skill with this name already exists')
                        ->setCode(Response::HTTP_CONFLICT);
                }

                $skill->setName($dto->name);
            }

            if ($dto->icon !== null) {
                $skill->setIcon($dto->icon);
            }

            if ($dto->yearsOfExperience !== null) {
                $skill->setYearsOfExperience($dto->yearsOfExperience);
            }

            if ($dto->isFeatured !== null) {
                $skill->setIsFeatured($dto->isFeatured);
            }

            $this->em->flush();

            return (new SuccessResponse())
                ->setData(SkillOutputDto::fromEntity($skill)->toArray())
                ->setMessage('Skill updated successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Delete a skill
     */
    public function deleteSkill(Skill $skill, User $user): IResponseArrayFormat
    {
        try {
            // Verify ownership
            if ($skill->getUser()?->getId() !== $user->getId()) {
                return (new ErrorResponse())
                    ->setMessage('You do not own this skill')
                    ->setCode(Response::HTTP_FORBIDDEN);
            }

            $this->em->remove($skill);
            $this->em->flush();

            return (new SuccessResponse())->setMessage('Skill deleted successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }
}
