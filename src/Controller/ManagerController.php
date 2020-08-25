<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AddAgentType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    /**
     * @Route("/manager", name="manager")
     */
    public function index()
    {
        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
        ]);
    }

    /**
     * @Route("/manager/add-agent", name="add-agent")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAgent(Request $request)
    {
        $message = "Create a user here";

        if($request->get('add_agent')){
            $data = $request->request->get('add_agent');
            $firstName = $data['first_name'];
            $lastName = $data['last_name'];
            $email = $data['email'];
            $random = bin2hex(random_bytes(6));

            $message = "Created agent {$firstName} {$lastName} with email {$email} and random generated password {$random}";

            $agent = new Users();
            $agent
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPassword($random)
                ->setRole('Agent')
            ;
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($agent);
            $manager->flush();
        }




        $user = new Users();
        $form = $this->createForm(AddAgentType::class, $user);
        return $this->render('manager/add_agent.html.twig', [
            'add_agent_form' => $form->createView(),
            'message' => $message

        ]);
    }
}