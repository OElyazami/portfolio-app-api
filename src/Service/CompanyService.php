<?php

namespace App\Service;

use App\Dto\Company\Input\CreateCompanyDto;
use App\Dto\Company\Input\UpdateCompanyDto;
use App\Dto\Company\Output\CompanyOutputDto;
use App\Entity\Company;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CompanyService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Get all companies for a user
     */
    public function getCompaniesByUser(User $user): IResponseArrayFormat
    {
        try {
            $companies = $this->em->getRepository(Company::class)
                ->findBy(['user' => $user], ['title' => 'ASC']);

            $data = array_map(
                fn(Company $company) => CompanyOutputDto::fromEntity($company)->toArray(),
                $companies
            );

            return (new SuccessResponse())->setData($data);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get a single company by ID
     */
    public function getCompany(int $id): IResponseArrayFormat
    {
        try {
            $company = $this->em->getRepository(Company::class)->find($id);

            if (!$company) {
                return (new ErrorResponse())
                    ->setMessage('Company not found')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            return (new SuccessResponse())->setData(CompanyOutputDto::fromEntity($company)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Create a new company
     */
    public function createCompany(CreateCompanyDto $dto, User $user): IResponseArrayFormat
    {
        try {
            $company = new Company();
            $company->setTitle($dto->title)
                    ->setDescription($dto->description)
                    ->setLogoImage($dto->logoImage)
                    ->setUser($user);

            $this->em->persist($company);
            $this->em->flush();

            return (new SuccessResponse())
                ->setData(CompanyOutputDto::fromEntity($company)->toArray())
                ->setCode(Response::HTTP_CREATED);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Update an existing company
     */
    public function updateCompany(UpdateCompanyDto $dto, Company $company, User $user): IResponseArrayFormat
    {
        try {
            // Verify ownership
            if ($company->getUser()?->getId() !== $user->getId()) {
                return (new ErrorResponse())
                    ->setMessage('You do not own this company')
                    ->setCode(Response::HTTP_FORBIDDEN);
            }

            if ($dto->title !== null) {
                $company->setTitle($dto->title);
            }

            if ($dto->description !== null) {
                $company->setDescription($dto->description);
            }

            if ($dto->logoImage !== null) {
                $company->setLogoImage($dto->logoImage);
            }

            $this->em->flush();

            return (new SuccessResponse())
                ->setData(CompanyOutputDto::fromEntity($company)->toArray())
                ->setMessage('Company updated successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Delete a company
     */
    public function deleteCompany(Company $company, User $user): IResponseArrayFormat
    {
        try {
            // Verify ownership
            if ($company->getUser()?->getId() !== $user->getId()) {
                return (new ErrorResponse())
                    ->setMessage('You do not own this company')
                    ->setCode(Response::HTTP_FORBIDDEN);
            }

            if ($company->getProjects()->count() > 0) {
                return (new ErrorResponse())
                    ->setMessage('Cannot delete company that has projects assigned')
                    ->setCode(Response::HTTP_CONFLICT);
            }

            $this->em->remove($company);
            $this->em->flush();

            return (new SuccessResponse())->setMessage('Company deleted successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }
}
