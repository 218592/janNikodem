<?php

namespace App\Controller;

use App\Entity\Rani;
use App\Entity\RatownicyWAkcji;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
            'nameWorkout' => $username,
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
            'nameWorkout' => $username,
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
     * @Route("/user/profil/add-kam", name="addKam")
     */
    public function addKam()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();

        $username = $user->getUsername();
        $userId = $user->getId();

        $formIsOk = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['akcja_id'])) {
            $akcja_id = $_POST['akcja_id'];

            //wstaw do tabeli user
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository(User::class)->find($userId);
            $u->setStatus("zajety");
            $u->setAkcjaId($akcja_id);
            $em->persist($u);

            $ratownikWAkcji = new RatownicyWAkcji();
            $ratownikWAkcji->setRatownikId($userId);
            $ratownikWAkcji->setCzyKam("KAM");
            $ratownikWAkcji->setAkcjaId($akcja_id);
            $em->persist($ratownikWAkcji);
            $em->flush();

            $_POST = array();

            $formIsOk = 1;
        }

        return $this->render('workout/addkam.html.twig', array(
            'namePage' => 'Triaz App - Dodaj Kam',
            'nameWorkout' => $username,
            'nav' => '1',
            'footer' => 1,
            'formIsOk' => $formIsOk,
        ));
    }

    /**
     * @Route("/user/profil/remove-kam", name="remove-kam")
     */
    public function removeKam()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $akcjaId = $user->getAkcjaId();
        $username = $user->getUsername();
        $userId = $user->getId();

        $formIsOk = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //usun ratowników z akcji
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'DELETE FROM App\Entity\RatownicyWAkcji r
                WHERE r.akcja_id = :idA'
            )->setParameter('idA', $akcjaId);
            $result = $query->getResult();

            //ustaw siebie jako wolny od KAM
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository(User::class)->find($userId);
            $u->setStatus(NULL);
            $u->setAkcjaId(NuLL);
            $em->persist($u);
            $em->flush();

            $formIsOk = 1;

        }

        return $this->render('workout/remove-kam.html.twig', array(
            'namePage' => 'Triaz App - Dodaj Kam',
            'nameWorkout' => $username,
            'nav' => '1',
            'footer' => 1,
            'formIsOk' => $formIsOk,
        ));

    }

}
