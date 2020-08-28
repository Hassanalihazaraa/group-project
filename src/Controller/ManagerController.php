<?php

namespace App\Controller;

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
        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
        ]);
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
                ->setRole('Agent');
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
}