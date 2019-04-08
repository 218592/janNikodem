<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;


class LoginController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/user/login", name="user_login")
     */
    public function loginSecurityUser(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('firstPageLogin/firstPageLogin.html.twig', array(
            'namePage' => 'Triaz App - login',
            'nav' => '1',
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    
    }
















    /**
     * @Route("/forgot-pass", name="forgotPass")
     */
    public function forgotPass(ObjectManager $manager, \Swift_Mailer $mailer)
    {

        $errorCode=0;
        $error="ok";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['email'])) {
                
                $email=$_POST['email'];

                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $error="Niepoprawny adres email";
                    $errorCode=1;
                }
                else
                {
                    $query = $this->getDoctrine()->getRepository(User::class);
                    $user = $query->findOneBy(['email' => $email]);

                    if(!$user)
                    {
                       $error="Twoje konto nie istnieje w serwisie";
                       $errorCode=1;
                    } 
                    else
                    {
                        $token=md5(uniqid());

                        $user->setActiveTokenMail($token);
                        $manager->persist($user);
                        $manager->flush();

                        $session = new Session();
                        $session->set('email', $email);
                        $session->set('token', '42');

                        //send email with active link
                        $body="Wiadomość automatyczna. Proszę na nią nie odpowiadać. Została aktywowana procedura przypominania hasła. Jeśli nie chcesz zmieniać hasła zignoruj wiadomość. Przekierowanie do strony głównej serwisu oznacza, że link wygasł. Należy wygenerować nowy. Przypomnij hasło: "."http://triaz/forgot-pass-account/".$token."";

                        $message = (new \Swift_Message("Zmień swoje hasło - Triaz App"))
                          ->setFrom(['triazapp@gmail.com' => 'Triaz App'])
                          ->setTo($email)
                          ->setBody($body);
                        $mailer->send($message);

                        if ($mailer->send($message))
                        {
                            return $this->redirectToRoute('forgotPassAfter', array(
                                // 'tokenRegisterAfter' => '42',
                            ));
                       
                        }

                        

                    }
                }

            }

        }

        return $this->render('firstPageLogin/firstPageForgotPass.html.twig', array(
            'namePage' => 'Triaz App - forgotPass',
            'nav' => '1',
            'error' => $error,
            'undo' => '1',
            'errorCode' => $errorCode,
        ));
    }



    /**
     * @Route("/forgot-pass-after", name="forgotPassAfter")
     */
    public function afterForgotPass()
    {       
        $session = new Session();
        $token=$session->get('token');
        $email=$session->get('email');
       
        $session->set('token', '0');
        $session->set('email', '0');
        
        return $this->render('firstPageLogin/firstPageForgotPassAfter.html.twig', array(
           'namePage' => 'After Forgot Pass',
           'redirect' => $token,
           'email' => $email,
        ));
    }


    /**
     * @Route("/forgot-pass-account/{tokenFromEmail}", name="forgotPassAccount")
     */
    public function forgotPassAccount(ObjectManager $manager, $tokenFromEmail)
    {       
        $token = $tokenFromEmail;  
        $query = $this->getDoctrine()->getRepository(User::class);
        $user = $query->findOneBy(['activeTokenMail' => $token]);

        if(!$user)
        {
            // upps nie ma uzytkownika, redirect
             return $this->redirectToRoute('firstPage', array(
                 'namePage' => 'Triaz App',
            ));
        } 
        else
        {
            $session = new Session();
            $session->set('token', $token);
            return $this->redirectToRoute('changePass', array(
            ));
        }
        
    }


    /**
     * @Route("/change-pass", name="changePass")
     */
    public function changePass(ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder)
    {       
        $session = new Session();
        $token=$session->get('token');

        $errorCode=0;
        $error="ok";
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['btnChangePass'])) {

                $pass=$_POST["pass"];
                $pass2=$_POST["pass2"];

                if ((!($pass==$pass2)) || empty($pass))
                {
                    $error="Hasła nie są takie same!";
                    $errorCode=1;
                }

                if ($errorCode==0)
                {
                    $query = $this->getDoctrine()->getRepository(User::class);
                    $user = $query->findOneBy(['activeTokenMail' => $token]);

                    if(!$user)
                    {
                       $error="Upss.. Brak użytkownika w bazie!";
                       $errorCode=1;
                    } 
                    else
                    {
                        $session->set('token', '0');
                        $encoded = $this->encoder->encodePassword($user, $pass);
                        $user->setPassword($encoded);
                        $username=$user->getUsername();

                        $session = new Session();
                        $session->set('token', '1764');
                        $session->set('username', $username);

                        //zmien token zeby wygasl link od email
                        $user->setActiveTokenMail(-42);
                        //aktywuj konto jesli nie bylo aktywowane / zguba linka aktywacyjnego? 
                        $user->setIsActive(true);
                        $manager->flush();

                        return $this->redirectToRoute('changePassAfter', array(
                            ));
                    }
                }
            }
        }
        
        return $this->render('firstPageLogin/firstPageChangePass.html.twig', array(
           'namePage' => 'After Forgot Pass',
           'nav' => '1',
           'redirect' => 1,
           'error' => $error,
           'errorCode' => $errorCode,
        ));
    }

    /**
     * @Route("/change-pass-after", name="changePassAfter")
     */
    public function changePassAfter()
    {       
        $session = new Session();
        $token=$session->get('token');
        $username=$session->get('username');
       
        $session->set('token', '0');
        $session->set('username', '0');
        
        return $this->render('firstPageLogin/firstPageChangePassAfter.html.twig', array(
           'namePage' => 'Change Pass After',
           'redirect' => $token,
           'username' => $username,
        ));
    }

    


}