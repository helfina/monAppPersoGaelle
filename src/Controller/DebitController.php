<?php

namespace App\Controller;

use App\Entity\Debit;
use App\Form\DebitType;
use App\Repository\DebitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/debit')]
class DebitController extends AbstractController
{
    #[Route('/', name: 'app_debit_index', methods: ['GET'])]
    public function index(DebitRepository $debitRepository): Response
    {
        return $this->render('debit/index.html.twig', [
            'debits' => $debitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_debit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DebitRepository $debitRepository): Response
    {
        $debit = new Debit();
        $form = $this->createForm(DebitType::class, $debit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debitRepository->save($debit, true);

            return $this->redirectToRoute('app_debit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('debit/new.html.twig', [
            'debit' => $debit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_debit_show', methods: ['GET'])]
    public function show(Debit $debit): Response
    {
        return $this->render('debit/show.html.twig', [
            'debit' => $debit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_debit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Debit $debit, DebitRepository $debitRepository): Response
    {
        $form = $this->createForm(DebitType::class, $debit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debitRepository->save($debit, true);

            return $this->redirectToRoute('app_debit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('debit/edit.html.twig', [
            'debit' => $debit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_debit_delete', methods: ['POST'])]
    public function delete(Request $request, Debit $debit, DebitRepository $debitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$debit->getId(), $request->request->get('_token'))) {
            $debitRepository->remove($debit, true);
        }

        return $this->redirectToRoute('app_debit_index', [], Response::HTTP_SEE_OTHER);
    }
}
