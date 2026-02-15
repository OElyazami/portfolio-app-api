<?php

namespace App\Controller\Api;

use App\Dto\Client\Input\CreateClientDto;
use App\Dto\Client\Input\UpdateClientDto;
use App\Entity\Client;
use App\Response\ErrorResponse;
use App\Service\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('client', name: 'client_')]
class ClientController extends AbstractController
{
    public function __construct(
        private ClientService $clientService,
        private EntityManagerInterface $em
    ) {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $response = $this->clientService->getClients();
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $response = $this->clientService->getClient($id);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateClientDto $dto
    ): JsonResponse {
        $response = $this->clientService->createClient($dto);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateClientDto $dto
    ): JsonResponse {
        $client = $this->findClientOrFail($id);
        if ($client instanceof JsonResponse) {
            return $client;
        }

        $response = $this->clientService->updateClient($dto, $client);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): JsonResponse
    {
        $client = $this->findClientOrFail($id);
        if ($client instanceof JsonResponse) {
            return $client;
        }

        $response = $this->clientService->deleteClient($client);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Find a client or return an error JsonResponse
     */
    private function findClientOrFail(int $id): Client|JsonResponse
    {
        $client = $this->em->getRepository(Client::class)->find($id);

        if (!$client) {
            $errorResponse = (new ErrorResponse())
                ->setMessage('Client not found')
                ->setCode(Response::HTTP_NOT_FOUND);
            return $this->json($errorResponse->getArrayFormat(), $errorResponse->getCode());
        }

        return $client;
    }
}
