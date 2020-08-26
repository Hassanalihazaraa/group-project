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
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
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
