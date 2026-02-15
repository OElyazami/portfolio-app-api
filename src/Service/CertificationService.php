<?php

namespace App\Service;

use App\Dto\Certification\Input\CreateCertificationDto;
use App\Dto\Certification\Input\UpdateCertificationDto;
use App\Dto\Certification\Output\CertificationOutputDto;
use App\Entity\Certification;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CertificationService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Get all certifications for a user
     */
    public function getCertificationsByUser(User $user): IResponseArrayFormat
    {
        try {
            $certifications = $this->em->getRepository(Certification::class)
                ->findBy(['user' => $user], ['title' => 'ASC']);

            $data = array_map(
                fn(Certification $cert) => CertificationOutputDto::fromEntity($cert)->toArray(),
                $certifications
            );

            return (new SuccessResponse())->setData($data);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get a single certification by ID
     */
    public function getCertification(int $id): IResponseArrayFormat
    {
        try {
            $cert = $this->em->getRepository(Certification::class)->find($id);

            if (!$cert) {
                return (new ErrorResponse())
                    ->setMessage('Certification not found')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            return (new SuccessResponse())->setData(CertificationOutputDto::fromEntity($cert)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Create a new certification
     */
    public function createCertification(CreateCertificationDto $dto, User $user): IResponseArrayFormat
    {
        try {
            $cert = new Certification();
            $cert->setTitle($dto->title)
                 ->setDescription($dto->description)
                 ->setUser($user);

            $this->em->persist($cert);
            $this->em->flush();

            return (new SuccessResponse())
                ->setData(CertificationOutputDto::fromEntity($cert)->toArray())
                ->setCode(Response::HTTP_CREATED);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Update an existing certification
     */
    public function updateCertification(UpdateCertificationDto $dto, Certification $cert, User $user): IResponseArrayFormat
    {
        try {
            // Verify ownership
            if ($cert->getUser()?->getId() !== $user->getId()) {
                return (new ErrorResponse())
                    ->setMessage('You do not own this certification')
                    ->setCode(Response::HTTP_FORBIDDEN);
            }

            if ($dto->title !== null) {
                $cert->setTitle($dto->title);
            }

            if ($dto->description !== null) {
                $cert->setDescription($dto->description);
            }

            $this->em->flush();

            return (new SuccessResponse())
                ->setData(CertificationOutputDto::fromEntity($cert)->toArray())
                ->setMessage('Certification updated successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Delete a certification
     */
    public function deleteCertification(Certification $cert, User $user): IResponseArrayFormat
    {
        try {
            // Verify ownership
            if ($cert->getUser()?->getId() !== $user->getId()) {
                return (new ErrorResponse())
                    ->setMessage('You do not own this certification')
                    ->setCode(Response::HTTP_FORBIDDEN);
            }

            $this->em->remove($cert);
            $this->em->flush();

            return (new SuccessResponse())->setMessage('Certification deleted successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }
}
