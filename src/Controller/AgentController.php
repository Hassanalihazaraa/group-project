<?php

namespace App\Controller;

use App\Entity\CommentHistory;
use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            ['status' => $status],
            ['id' => 'ASC']
        );


        return $this->render('agent/index.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/agent/ticket/{id}", name="handle", methods={"GET", "POST"})
     * @param Ticket $ticket
     * @return Response
     */
    public function findHandleTickets(Ticket $ticket, Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $manager = $this->getDoctrine()->getManager();
        $user = $repository->find($ticket->getCreatedBy());
        $agent = $repository->find(1); // change with id of agent that's logged in
        $ticket->setStatus('In progress');
        $ticket->setHandlingAgent($agent);

        if($request->get('escalate')){
            $ticket->setIsEscalated(true);
            $ticket->setHandlingAgent(null);
        }
        if(!empty($request->get('comment'))){
            $newComment = new CommentHistory();
            $newComment
                ->setFromManager(false)
                ->setIsPrivate(false)
                ->setTicket($ticket)
                ->setCreatedBy($agent)
                ->setComments($request->get('comment'))
                ;
            $this->getDoctrine()->getManager()->persist($newComment);
            $this->getDoctrine()->getManager()->flush();
        }

        $manager->persist($ticket);
        $manager->flush();

        $comments = $this->getDoctrine()->getRepository(CommentHistory::class)->findBy(
            ['ticket' => $ticket->getId()],
            ['id' => 'DESC']
        );

        return $this->render('agent/handle.html.twig', [
            'ticket' => $ticket,
            'user' => $user,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/agent/personal_tickets", name="personal_tickets", methods={"GET"})
     * @return Response
     */
    public function showAgentTickets()
    {
        $agent = $this->getDoctrine()->getRepository(User::class)->find(1);
        $agentTickets = $this->getDoctrine()->getRepository(Ticket::class)->findBy(
            ['handling_agent' => $agent->getId()],
            ['id' => 'ASC']
        );
        return $this->render('agent/personaltickets.html.twig', [
            'tickets' => $agentTickets
        ]);
    }
}
