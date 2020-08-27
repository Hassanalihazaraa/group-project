<?php

namespace App\Controller;

use App\Entity\Ticket;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AgentController extends AbstractController
{
    /**
     * @Route("/agent", name="agent")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $status = 'open';
        $tickets = $repository->findBy(
            ['status' => $status]);


        return $this->render('agent/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * @Route("/agent/ticket/{id}", name="handle", methods={"GET"})
     * @param Ticket $ticket
     * @return Response
     */

    public function findHandleTickets(Ticket $ticket): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($ticket->getCreatedBy());

        return $this->render('agent/handle.html.twig', [
            'ticket' => $ticket,
            'user' => $user
        ]);
    }
}
