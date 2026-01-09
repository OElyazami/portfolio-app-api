<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('skill', name: 'skill_')]
class SkillController extends AbstractController {

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(){
        return $this->json('text');
    }
}