<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// class MesReservationsController extends AbstractController
// {
//     #[Route('/mes/reservations', name: 'app_mes_reservations')]
//     public function index(): Response
//     {
//         return $this->render('mes_reservations/index.html.twig', [
//             'controller_name' => 'MesReservationsController',
//         ]);
//     }
// }

// src/Controller/MesReservationsController.php

// namespace App\Controller;

// use App\Entity\Reservation;
// use App\Repository\ReservationRepository;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Security\Core\User\UserInterface;

// class MesReservationsController extends AbstractController
// {
//     #[Route('/mes-reservations', name: 'mes_reservations')]
//     public function index(ReservationRepository $reservationRepository, UserInterface $user): Response
//     {
//         // Fetch the reservations for the logged-in user
//         $reservations = $reservationRepository->findBy(['client' => $user]);

//         return $this->render('mes_reservations/index.html.twig', [
//             'reservations' => $reservations,
//         ]);
//     }
// }

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class MesReservationsController extends AbstractController
{
    #[Route('/mes-reservations', name: 'mes_reservations')]
    public function index(ReservationRepository $reservationRepository, UserInterface $user): Response
    {
        // Fetch the reservations for the logged-in user
        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('mes_reservations/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/reservation/cancel/{id}", name="reservation_cancel")
     */
    #[Route('/reservation/cancel/{id}', name: 'reservation_cancel')]
    public function cancel($id, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        // Find the reservation by ID
        $reservation = $reservationRepository->find($id);

        if ($reservation) {
            // Check if the reservation is associated with the current user
            $user = $this->getUser();
            if ($reservation->getUser() === $user) {
                // Cancel the reservation (remove it)
                $entityManager->remove($reservation);
                $entityManager->flush();

                $this->addFlash('success', 'Reservation cancelled successfully');
            } else {
                $this->addFlash('error', 'You are not authorized to cancel this reservation');
            }
        } else {
            $this->addFlash('error', 'Reservation not found');
        }

        return $this->redirectToRoute('mes_reservations');
    }
}

