<?php

namespace App\Controller;

use App\Entity\Rani;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserProfilController extends AbstractController
{
    /**
     * @Route("/user/profil", name="userProfil")
     */
    public function showProfil()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $username = $user->getUsername();

        return $this->render('workout/workoutProfil.html.twig', array(
            'namePage' => 'Triaz App - Profil',
            'nameWorkout' => 'my / ' . $username,
            'nav' => '1',
            'footer' => 1,
        ));
    }

    /**
     * @Route("/user/profil/data-now", name="data-now")
     */
    public function showDataNow()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $username = $user->getUsername();

        //selcet
        $akcja = 1;
        $query = $this->getDoctrine()
            ->getRepository(Rani::class)->createQueryBuilder('r')
            ->andWhere('r.akcjaId = :akcja')
            ->setParameter('akcja', $akcja)
            ->getQuery();

        $rani = $query->getArrayResult();
        // print("<pre>" . print_r($rani2, true) . "</pre>");
        // $rani = $query->execute();

        return $this->render('workout/data-now.html.twig', array(
            'rani' => $rani,
            'namePage' => 'Triaz App - Profil',
            'nameWorkout' => 'my / ' . $username,
            'nav' => '1',
            'footer' => 1,
        ));
    }

    /**
     * @Route("/user/profil/data-now/ajax", name="data-now-ajax")
     */
    public function showDataNowAjax()
    {
        //selcet
        $akcja = 1;
        $query = $this->getDoctrine()
            ->getRepository(Rani::class)->createQueryBuilder('r')
            ->andWhere('r.akcjaId = :akcja')
            ->setParameter('akcja', $akcja)
            ->getQuery();

        $rani = $query->getArrayResult();

        $msg = array(
            array('responseFlag' => $rani),
        );

        return new JsonResponse(array('ajaxResponseContactController' => $msg));

    }

    /**
     * @Route("/user/profil/data-history", name="data-history")
     */
    public function showDataHistory()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $username = $user->getUsername();

        return $this->render('workout/data-history.html.twig', array(
            'namePage' => 'Triaz App - Profil',
            'nameWorkout' => 'my / ' . $username,
            'nav' => '1',
            'footer' => 1,
        ));
    }

}
