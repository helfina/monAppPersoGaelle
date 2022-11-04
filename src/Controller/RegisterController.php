<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHashes): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        // ecoute la requete

        $form->handleRequest($request);
        // est-ce que mon formulaire a ete soumis et est ce que mon formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // tu inject dans l'objet user toutes le données que tu recupere dans le formulaire
            $user = $form->getData();
            dump($user);

            // hash du mdp
            $password = $passwordHashes->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            dump($password);

            // enregistrement en bdd
            $manager->persist($user); // je prepare et je fige la donner pour la creation de l'entity
            $manager->flush();
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
