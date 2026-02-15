<?php

namespace App\Controller\Api;

use App\Dto\Certification\Input\CreateCertificationDto;
use App\Dto\Certification\Input\UpdateCertificationDto;
use App\Entity\Certification;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Service\CertificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('certification', name: 'certification_')]
class CertificationController extends AbstractController
{
    public function __construct(
        private CertificationService $certificationService,
        private EntityManagerInterface $em
    ) {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->certificationService->getCertificationsByUser($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $response = $this->certificationService->getCertification($id);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateCertificationDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $response = $this->certificationService->createCertification($dto, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateCertificationDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $cert = $this->findCertificationOrFail($id);
        if ($cert instanceof JsonResponse) {
            return $cert;
        }

        $response = $this->certificationService->updateCertification($dto, $cert, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
        #[CurrentUser] User $user
    ): JsonResponse {
        $cert = $this->findCertificationOrFail($id);
        if ($cert instanceof JsonResponse) {
            return $cert;
        }

        $response = $this->certificationService->deleteCertification($cert, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Find a certification or return an error JsonResponse
     */
    private function findCertificationOrFail(int $id): Certification|JsonResponse
    {
        $cert = $this->em->getRepository(Certification::class)->find($id);

        if (!$cert) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('Certification not found')
                ->setCode(Response::HTTP_NOT_FOUND);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        return $cert;
    }
}
