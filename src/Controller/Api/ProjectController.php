<?php 

namespace App\Controller\Api;

use App\Dto\Project\CreateProjectDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('project', name: 'project_')]
class ProjectController extends AbstractController {

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(){

        return $this->json('text2');
    }

    #[Route('/create', name:'create', methods:['POST'])]
    public function create(
        #[MapRequestPayload]CreateProjectDto $dto
    ): JsonResponse
    {
        return $this->json("passed");
    }
}