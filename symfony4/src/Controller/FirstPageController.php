<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FirstPageController extends AbstractController
{
    /**
     * @Route("/", name="firstPage")
     */
    public function showFirstPage()
    {
        return $this->render('firstPage/firstPageIndex.html.twig', [ 
            'namePage' => 'Syssitia App',
        ]);
    }
 
}
