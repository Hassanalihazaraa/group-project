<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecondLineAgentController extends AbstractController
{
    /**
     * @Route("/second-line-agent", name="second_line_agent")
     */
    public function getAllTickets(): Response
    {
        $tickets = $this->getDoctrine()->getRepository(Ticket::class);

        return $this->render('second_line_agent/index.html.twig', [
            'escalatedTickets' => $tickets
        ]);
    }
}
