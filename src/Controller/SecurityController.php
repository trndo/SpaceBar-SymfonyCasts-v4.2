<?php

namespace App\Controller;


use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout",name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder,GuardAuthenticatorHandler $guardAuthenticatorHandler,LoginFormAuthenticator $loginFormAuthenticator)
    {
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setFirstName('Mister');
            $user->setPassword($userPasswordEncoder->encodePassword($user, $request->request->get('password')));


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main');
        }

        return $this->render('security/register.html.twig');
    }
}
