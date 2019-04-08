<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends AbstractController
{
   
    /**
     * @Route("/contact", name="contact")
     */
    public function showcontact()
    {
        return $this->render('firstPageContact/firstPageContact.html.twig', [ 
            'namePage' => 'Syssitia App - contact',
            'nav' => '1',
            'footer' => '1',
        ]);
    }


    /**
    * @Route("/contact/ajaxContactController", name="ajaxContactController")
    */
    public function sendMail(Request $request, \Swift_Mailer $mailer)
    {      
        // pobierz
        $data1 = $request->get('email');
        $data2 = $request->get('name');
        $data3 = $request->get('surname');
        $data4 = $request->get('topic');
        $data5 = $request->get('textArea');

        // walidacja poprawnosci maila "."
        if (filter_var($data1, FILTER_VALIDATE_EMAIL)) {

            $flag = 1;
            $message = (new \Swift_Message($data4))
                ->setFrom([$data1 => $data2 . ' ' . $data3])
                ->setCc([$data1 => $data2 . ' ' . $data3])
                ->setTo('garnela32@gmail.com')
                ->setBody($data5);
            $mailer->send($message);   
        }
        else{
            $flag = 0;
        }

        //pokaz komunikat
        $msg = array(
            array('responseFlag' => $flag),
        );
        
        return new JsonResponse(array('ajaxResponseContactController' => $msg));
    }
}
