<?php

namespace App\Controller;

use App\Entity\Mois;
use App\Form\MoisType;
use App\Repository\MoisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mois')]
class MoisController extends AbstractController
{
    #[Route('/', name: 'app_mois_index', methods: ['GET'])]
    public function index(MoisRepository $moisRepository): Response
    {
        return $this->render('mois/index.html.twig', [
            'mois' => $moisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mois_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MoisRepository $moisRepository): Response
    {
        $moi = new Mois();
        $form = $this->createForm(MoisType::class, $moi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moisRepository->save($moi, true);

            return $this->redirectToRoute('app_mois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mois/new.html.twig', [
            'moi' => $moi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mois_show', methods: ['GET'])]
    public function show(Mois $moi): Response
    {
        return $this->render('mois/show.html.twig', [
            'moi' => $moi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mois_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mois $moi, MoisRepository $moisRepository): Response
    {
        $form = $this->createForm(MoisType::class, $moi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moisRepository->save($moi, true);

            return $this->redirectToRoute('app_mois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mois/edit.html.twig', [
            'moi' => $moi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mois_delete', methods: ['POST'])]
    public function delete(Request $request, Mois $moi, MoisRepository $moisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moi->getId(), $request->request->get('_token'))) {
            $moisRepository->remove($moi, true);
        }

        return $this->redirectToRoute('app_mois_index', [], Response::HTTP_SEE_OTHER);
    }
}
