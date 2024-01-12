<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\RegistrationFormType;
use App\Form\AddressFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        // $address = new Address();

        $form = $this->createForm(RegistrationFormType::class, $user);
        // $addressForm = $this->createForm(AddressFormType::class, $address);

        $form->handleRequest($request);
        // $addressForm->handleRequest($request);

        // débogages
        // dump($user);
        // dump($form->getErrors(true));

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Lier l'adresse à l'utilisateur
            // $address->setUser($user);

            $entityManager->persist($user);
            $entityManager->flush();

            // $entityManager->persist($address);
            // $entityManager->flush();


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            // 'addressForm' => $addressForm->createView(),
        ]);
    }
}