<?php

namespace App\Service;

use App\Dto\Client\Input\CreateClientDto;
use App\Dto\Client\Input\UpdateClientDto;
use App\Dto\Client\Output\ClientOutputDto;
use App\Entity\Client;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ClientService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Get all clients
     */
    public function getClients(): IResponseArrayFormat
    {
        try {
            $clients = $this->em->getRepository(Client::class)
                ->findBy([], ['name' => 'ASC']);

            $data = array_map(
                fn(Client $client) => ClientOutputDto::fromEntity($client)->toArray(),
                $clients
            );

            return (new SuccessResponse())->setData($data);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get a single client by ID
     */
    public function getClient(int $id): IResponseArrayFormat
    {
        try {
            $client = $this->em->getRepository(Client::class)->find($id);

            if (!$client) {
                return (new ErrorResponse())
                    ->setMessage('Client not found')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            return (new SuccessResponse())->setData(ClientOutputDto::fromEntity($client)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Create a new client
     */
    public function createClient(CreateClientDto $dto): IResponseArrayFormat
    {
        try {
            // Check for duplicate name
            $existing = $this->em->getRepository(Client::class)
                ->findOneBy(['name' => $dto->name]);

            if ($existing) {
                return (new ErrorResponse())
                    ->setMessage('A client with this name already exists')
                    ->setCode(Response::HTTP_CONFLICT);
            }

            $client = new Client();
            $client->setName($dto->name)
                   ->setLogoImage($dto->logoImage);

            $this->em->persist($client);
            $this->em->flush();

            return (new SuccessResponse())
                ->setData(ClientOutputDto::fromEntity($client)->toArray())
                ->setCode(Response::HTTP_CREATED);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Update an existing client
     */
    public function updateClient(UpdateClientDto $dto, Client $client): IResponseArrayFormat
    {
        try {
            if ($dto->name !== null) {
                // Check for duplicate name (excluding current)
                $existing = $this->em->getRepository(Client::class)
                    ->findOneBy(['name' => $dto->name]);

                if ($existing && $existing->getId() !== $client->getId()) {
                    return (new ErrorResponse())
                        ->setMessage('A client with this name already exists')
                        ->setCode(Response::HTTP_CONFLICT);
                }

                $client->setName($dto->name);
            }

            if ($dto->logoImage !== null) {
                $client->setLogoImage($dto->logoImage);
            }

            $this->em->flush();

            return (new SuccessResponse())
                ->setData(ClientOutputDto::fromEntity($client)->toArray())
                ->setMessage('Client updated successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Delete a client
     */
    public function deleteClient(Client $client): IResponseArrayFormat
    {
        try {
            if ($client->getProjects()->count() > 0) {
                return (new ErrorResponse())
                    ->setMessage('Cannot delete client that has projects assigned')
                    ->setCode(Response::HTTP_CONFLICT);
            }

            $this->em->remove($client);
            $this->em->flush();

            return (new SuccessResponse())->setMessage('Client deleted successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }
}
