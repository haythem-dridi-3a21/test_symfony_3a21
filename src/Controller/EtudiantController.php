<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantFormType;
use App\Repository\EtudiantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EtudiantController extends AbstractController
{
    #[Route('/etudiants', name: 'etudiants.index')]
    public function index(EtudiantRepository $etudiantRepository): Response
    {

        $etudiants = $etudiantRepository->findAll();

        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }

    #[Route('/etudiants/create', name: 'etudiants.create')]
    public function create(Request $request, ManagerRegistry $managerRegistryy): Response
    {
        $etudiant = new Etudiant();

        $form = $this->createForm(EtudiantFormType::class, $etudiant);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $managerRegistryy->getManager();

            $em->persist($etudiant);

            $em->flush();

            return $this->redirectToRoute('etudiants.index');
        }

        return $this->render('etudiant/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/etudiants/{id}/edit', name: 'etudiants.edit')]
    public function edit($id, Request $request, ManagerRegistry $manager, EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = $etudiantRepository->find($id);

        $form = $this->createForm(EtudiantFormType::class, $etudiant);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $manager->getManager();

            $em->flush();

            return $this->redirectToRoute('etudiants.index');
        }

        return $this->render('etudiant/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/etudiants/{id}/destroy', name: 'etudiants.destory')]
    public function destroy($id, EtudiantRepository $etudiantRepository, ManagerRegistry $managerRegistry)
    {
        $etudiant = $etudiantRepository->find($id);

        $em = $managerRegistry->getManager();

        $em->remove($etudiant);

        $em->flush();

        return $this->redirectToRoute('etudiants.index');
    }
}
