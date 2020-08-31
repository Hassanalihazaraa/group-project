<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use App\Form\AddAgentType;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ManagerController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    /**
     * @Route("/manager", name="manager")
     */
    public function index(): Response
    {
        return $this->render('manager/manager.html.twig');
    }

    /**
     * @Route("/manager/add-agent", name="add-agent")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function addAgent(Request $request, MailerInterface $mailer): Response
    {
        $message = "Create a user here";

        if ($request->get('add_agent')) {
            $data = $request->request->get('add_agent');
            $firstName = $data['first_name'];
            $lastName = $data['last_name'];
            $email = $data['email'];
            $random = bin2hex(random_bytes(6));

            $message = "Created agent {$firstName} {$lastName} with email {$email} and random generated password {$random}";

            $agent = new User();
            $agent
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPassword($random)
                ->setRole('ROLE_AGENT');
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($agent);
            $manager->flush();

            //send reset password email to agent
            $agents = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $data['email'],
            ]);
            $this->setCanCheckEmailInSession();
            // Do not reveal whether a user account was found or not.
            if (!$agents) {
                return $this->redirectToRoute('app_check_email');
            }

            try {
                $resetToken = $this->resetPasswordHelper->generateResetToken($agents);
            } catch (ResetPasswordExceptionInterface $e) {
                return $this->redirectToRoute('app_check_email');
            }

            $email = (new TemplatedEmail())
                ->from(new Address('newpassword@gmail.com', 'Acme mail bot'))
                ->to($agents->getEmail())
                ->subject('Your password reset request')
                ->htmlTemplate('reset_password/email.html.twig')
                ->context([
                    'resetToken' => $resetToken,
                    'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_check_email');
        }

        $user = new User();
        $form = $this->createForm(AddAgentType::class, $user);
        return $this->render('manager/add_agent.html.twig', [
            'add_agent_form' => $form->createView(),
            'message' => $message

        ]);
    }

    /**
     * @Route("/manager/all_agents", name="check_agents")
     */
    public function showAgents(): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $agents = $repository->findBy([
            'roles' => 'ROLE_AGENT'
        ]);
        return $this->render('manager/all_agents.html.twig', [
            'agents' => $agents
        ]);
    }
    /**
     * @Route("/manager/agent_detail", name="agent_detail")
     */
    public function agentDetails(): Response
    {
        return $this->render('manager/index.html.twig', [

        ]);
    }

    /**
     * @Route("/manager/all_tickets", name="check_tickets")
     */
    public function checkTickets(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $tickets = $repository->findAll();
        return $this->render('manager/all_tickets.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/manager/ticket_detail", name="ticket_detail")
     */
    public function ticketDetails(): Response
    {
        return $this->render('manager/index.html.twig', [

        ]);
    }
}