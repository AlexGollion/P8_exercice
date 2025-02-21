<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VoitureRepository;

final class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_voiture', methods: ['GET'])]
    public function index(VoitureRepository $repository): Response
    {
        $voitures = $repository->findAll();

        return $this->render('voiture/Accueil.html.twig', [
            'controller_name' => 'VoitureController',
            'voitures' => $voitures,
        ]);
    }
}
