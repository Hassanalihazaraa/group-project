<?php

namespace App\Controller;

use App\Entity\CommentHistory;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\NewTicketType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer")
     */
    public function index(): Response
    {
        $defaultmessage = "You have no open tickets!";
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $userId = 1;
        $tickets = $repository->findBy(
            ['created_by' => $userId],
            ['id' => 'ASC']
        );


        return $this->render('customer/index.html.twig', [
            'tickets' => $tickets,
            'message' => $defaultmessage
        ]);
    }

    /**
     * @Route("/customer/ticket/{id}", name="customer-ticket-details", methods={"GET", "POST"})
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function ticketDetail(Ticket $ticket, Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($ticket->getCreatedBy());
        $displayCommentField = false;
        $status = $ticket->getStatus();
        //$data = $request->get("save_comment");

        if ($request->get("add_comment")) {
            $displayCommentField = true;
        }

        if($request->get('reopen') && $status === 'Closed'){
            $ticket->setStatus('open');
            $ticket->setUpdatedMessageTime(new \DateTimeImmutable());
        }
        if(!empty($request->get('comment'))){
            $manager = $this->getDoctrine()->getManager();
            $ticket->setUpdatedMessageTime(new \DateTimeImmutable());
            $newComment = new CommentHistory();
            $newComment
                ->setCreatedBy($user)
                ->setComments($request->get('comment'))
                ->setTicket($ticket)
                ->setIsPrivate(false) //set defaults in constructor
                ->setFromManager(false);
            $manager->persist($newComment);
            $manager->persist($ticket);
            $manager->flush();
        }
        $comments = $this->getDoctrine()->getRepository(CommentHistory::class)->findBy(
            ['ticket' => $ticket->getId()],
            ['id' => 'DESC']
        );

        $this->getDoctrine()->getManager()->persist($ticket);
        $this->getDoctrine()->getManager()->flush();

        $lastResponse = $ticket->getUpdatedMessageTime();
        $status = $ticket->getStatus();
        return $this->render('customer/ticket_detail.html.twig', [
            'ticket' => $ticket,
            'user' => $user,
            'displayfield' => $displayCommentField,
            'comments' => $comments,
            'lastResponse' => $lastResponse,
            'status' => $status

        ]);
    }

    /**
     * @Route("/customer/new_ticket", name="new_ticket", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function newTicket(Request $request): Response
    {
        $message = "Create a ticket here";

        $user = $this->getDoctrine()->getRepository(User::class)->find(1);


        if ($request->get('new_ticket')) {
            $data = $request->request->get('new_ticket');
            //$createdBy = $data['created_by'];
            $title = $data['title'];
            $content = $data['message'];
            //$user_id = 1; // change to session user_id

            $ticket = new Ticket();
            $ticket->setDefaults();
            $ticket->setTitle($title);
            $ticket->setMessage($content);
            $ticket->setCreatedBy($user);

            $message = 'Ticket created';

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ticket);
            $manager->flush();
        }


        $ticket = new Ticket();
        $form = $this->createForm(NewTicketType::class, $ticket);
        return $this->render('user/new_ticket.html.twig', [
            'new_ticket_form' => $form->createView(),
            'message' => $message

        ]);
    }
}
