<?php

namespace App\Controller;

use App\Entity\CommentHistory;
use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecondLineAgentController extends AbstractController
{
    /**
     * @Route("/second/line/agent", name="second_line_agent")
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        //$status = 'is_escalated';
        $tickets = $repository->findBy(
            //['is_escalated' => $status],
            ['is_escalated' => true],
            ['id' => 'ASC']
        );

        return $this->render('second_line_agent/index.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/second/line/agent/ticket/{id}", name="handle_escalated_ticket", methods={"GET", "POST"})
     * @param Ticket $ticket
     * @return Response
     */
    public function hand_escalated_tickets(Ticket $ticket, Request $request) : response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $manager = $this->getDoctrine()->getManager();
        $user = $repository->find($ticket->getCreatedBy());
        $secondAgent = $repository->find(1); // change with id of second agent that's logged in
        $ticket->setStatus('In progress');
        $ticket->setHandlingAgent($secondAgent);

        if($request->get('escalate')){
            $ticket->setIsEscalated(true);
            $ticket->setHandlingAgent(null);
        }
        if(!empty($request->get('comment'))){
            $escalatedComment = new CommentHistory();
            $escalatedComment
                ->setFromManager(false)
                ->setIsPrivate(false)
                ->setTicket($ticket)
                ->setCreatedBy($secondAgent)
                ->setComments($request->get('comment'))
            ;
            $this->getDoctrine()->getManager()->persist($escalatedComment);
            $this->getDoctrine()->getManager()->flush();
        }

        $manager->persist($ticket);
        $manager->flush();

        $comments = $this->getDoctrine()->getRepository(CommentHistory::class)->findBy(
            ['ticket' => $ticket->getId()],
            ['id' => 'DESC']
        );

        return $this->render('second_line_agent/handle_escalated.html.twig', [
            'ticket' => $ticket,
            'user' => $user,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/second/line/agent/personal_escalated_tickets", name="personal_escalated_tickets", methods={"GET", "POST"})
     * @return Response
     */
    public function showAgentTickets()
    {
        $secondAgent = $this->getDoctrine()->getRepository(User::class)->find(1);
        $secondAgentTickets = $this->getDoctrine()->getRepository(Ticket::class)->findBy(
            ['handling_agent' => $secondAgent->getId()],
            //['escalated' => true], //can be commented out once login is linked to user, now everything for user id 1
            ['id' => 'ASC']
        );
        return $this->render('agent/personaltickets.html.twig', [
            'tickets' => $secondAgentTickets
        ]);
    }


}
