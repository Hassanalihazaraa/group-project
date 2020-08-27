<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class SecondLineAgentController extends AbstractController
{
    /**
     * @Route("/second-line-agent", name="second_line_agent")
     */
    public function getAllTickets(): Response
    {
        $escalatedTickets[] = "";
        $tickets = $this->getDoctrine()->getRepository(User::class);
        if ($tickets) {
            if ($tickets === null) {
                throw new HttpException(404, "tickets not found");
            }
            foreach ($tickets as $ticket) {
                $escalatedTickets[] = $ticket;
            }
        }

        return $this->render('second_line_agent/index.html.twig', [
            'escalatedTickets' => $escalatedTickets
        ]);
    }
}
