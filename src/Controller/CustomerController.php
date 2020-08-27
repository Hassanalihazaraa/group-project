<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use App\Form\NewTicketType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer")
     */
    public function index()
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
     * @Route("/customer/ticket/{id}", name="customer-ticket-details", methods={"GET"})
     */
    public function ticketDetail(Ticket $ticket): \Symfony\Component\HttpFoundation\Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($ticket->getCreatedBy());
        return $this->render('customer/ticket_detail.html.twig', [
            'ticket' => $ticket,
            'user' => $user
        ]);
    }

    /**
     * @Route("/customer/new_ticket", name="new_ticket", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newTicket(Request $request)
    {
        $message = "Create a ticket here";

        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        //$user = $repository->;

        if($request->get('new_ticket')){
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
