<?php

namespace App\Controller;

use App\Entity\Taxes;
use App\Form\TaxesType;
use App\Repository\TaxesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/taxes')]
class TaxesController extends AbstractController
{
    #[Route('/', name: 'app_taxes_index', methods: ['GET'])]
    public function index(TaxesRepository $taxRepository): Response
    {
        return $this->render('taxes/index.html.twig', [
            'taxes' => $taxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_taxes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tax = new Taxes();
        $form = $this->createForm(TaxesType::class, $tax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tax);
            $entityManager->flush();

            return $this->redirectToRoute('app_taxes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('taxes/new.html.twig', [
            'tax' => $tax,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taxes_show', methods: ['GET'])]
    public function show(Taxes $tax): Response
    {
        return $this->render('taxes/show.html.twig', [
            'tax' => $tax,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_taxes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Taxes $tax, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaxesType::class, $tax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_taxes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('taxes/edit.html.twig', [
            'tax' => $tax,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taxes_delete', methods: ['POST'])]
    public function delete(Request $request, Taxes $tax, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tax->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tax);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_taxes_index', [], Response::HTTP_SEE_OTHER);
    }
}
