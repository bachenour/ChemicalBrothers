<?php

namespace App\Controller;

use App\Entity\FDS;
use App\Form\FDSType;
use App\Repository\FDSRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use Dompdf\Dompdf;

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

    #[Route('/pdf/generator/{id}', name: 'app_fds_pdf_generator')]
    public function pdf(Request $request, FDSRepository $fDSRepository, ProductRepository $productRepo): Response
    {
        $fds = $fDSRepository->findOneBy(['id' => $request->get('id')]);
        $product = $productRepo->findOneBy(['fds' => $fds]);
        // return $this->render('pdf_generator/index.html.twig', [
        //     'controller_name' => 'PdfGeneratorController',
        // ]);
        $data = [
            'productName' => $product->getName(),
            'id' => $fds->getId(),
            'createdAt' => $fds->getCreatedAt(),
            'updatedAt' => $fds->getUpdatedAt(),
            'version' => $fds->getVersion(),
            'chemicalName' => $fds->getChemicalName(),
            'practice' => $fds->getPractice(),
            'dangerWarnings' => $fds->getDangerWarnings(),
            'cautionaryAdvice' => $fds->getCautionaryAdvice()
        ];
        $html =  $this->renderView('pdf_generator/index.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}
