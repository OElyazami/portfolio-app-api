<?php

namespace App\Controller\Api;

use App\Dto\Company\Input\CreateCompanyDto;
use App\Dto\Company\Input\UpdateCompanyDto;
use App\Entity\Company;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Service\CompanyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('company', name: 'company_')]
class CompanyController extends AbstractController
{
    public function __construct(
        private CompanyService $companyService,
        private EntityManagerInterface $em
    ) {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->companyService->getCompaniesByUser($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $response = $this->companyService->getCompany($id);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateCompanyDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $response = $this->companyService->createCompany($dto, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateCompanyDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $company = $this->findCompanyOrFail($id);
        if ($company instanceof JsonResponse) {
            return $company;
        }

        $response = $this->companyService->updateCompany($dto, $company, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
        #[CurrentUser] User $user
    ): JsonResponse {
        $company = $this->findCompanyOrFail($id);
        if ($company instanceof JsonResponse) {
            return $company;
        }

        $response = $this->companyService->deleteCompany($company, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Find a company or return an error JsonResponse
     */
    private function findCompanyOrFail(int $id): Company|JsonResponse
    {
        $company = $this->em->getRepository(Company::class)->find($id);

        if (!$company) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('Company not found')
                ->setCode(Response::HTTP_NOT_FOUND);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        return $company;
    }
}
