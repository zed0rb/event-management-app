<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user/register/{id}', name: 'user_register', methods: ['GET', 'POST'])]
    public function register(Event $event, Request $request): Response
    {
        $user = new User();
        $user->setEvent($event);

        $form = $this->createForm(UserType::class, $user, ['event' => $event]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'You have successfully registered for the event.');

            return $this->redirectToRoute('event_list');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }
}
