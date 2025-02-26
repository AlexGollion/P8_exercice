<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\VoitureType;
use App\Entity\Voiture;
use Symfony\Component\HttpFoundation\Request;

final class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_voiture_home', methods: ['GET'])]
    public function index(VoitureRepository $repository): Response
    {
        $voitures = $repository->findAll();

        return $this->render('voiture/Accueil.html.twig', [
            'controller_name' => 'VoitureController',
            'voitures' => $voitures,
        ]);
    }
    
    #[Route('/voiture/{id}', name: 'app_voiture_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, VoitureRepository $repository): Response
    {
        $voiture = $repository->find($id);
        return $this->render('voiture/Voiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/voiture/{id}/suprimer', name: 'app_voiture_remove', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function remove(int $id, EntityManagerInterface $manager, VoitureRepository $repository): Response
    {
        $voiture = $repository->find($id);

        if ($voiture != null)
        {
            $manager->remove($voiture);
            $manager->flush();
        }
        return $this->redirectToRoute('app_voiture_home');
    }

    #[Route('/voiture/ajouter', name: 'app_voiture_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($voiture);
            $manager->flush();
            
            return $this->redirectToRoute('app_voiture_detail', ['id' => $voiture->getId()]);
        }
        return $this->render('voiture/nouvelle-voiture.html.twig', [
            'form' => $form,
        ]);
    }
}
