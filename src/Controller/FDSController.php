<?php

namespace App\Controller;

use App\Entity\FDS;
use App\Form\FDSType;
use App\Repository\FDSRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

#[Route('/fds')]
class FDSController extends AbstractController
{
    #[Route('/', name: 'app_fds_index', methods: ['GET'])]
    public function index(FDSRepository $fDSRepository): Response
    {
        return $this->render('fds/index.html.twig', [
            'fdss' => $fDSRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fds_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fds = new FDS();
        $form = $this->createForm(FDSType::class, $fds);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fds->setCreatedAt(new DateTimeImmutable('now'));
            $fds->setVersion(1.0);
            $entityManager->persist($fds);
            $entityManager->flush();

            return $this->redirectToRoute('app_fds_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fds/new.html.twig', [
            'fds' => $fds,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fds_show', methods: ['GET'])]
    public function show(FDS $fds): Response
    {
        return $this->render('fds/show.html.twig', [
            'fds' => $fds,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fds_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FDS $fds, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FDSType::class, $fds);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fds->setUpdatedAt(new DateTimeImmutable('now'));
            $entityManager->flush();

            return $this->redirectToRoute('app_fds_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fds/edit.html.twig', [
            'fds' => $fds,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fds_delete', methods: ['POST'])]
    public function delete(Request $request, FDS $fD, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fD->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fD);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fds_index', [], Response::HTTP_SEE_OTHER);
    }
}
