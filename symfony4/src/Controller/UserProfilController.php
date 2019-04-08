<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UserProfilController extends AbstractController
{
    /**
     * @Route("/user/profil", name="userProfil")
     */
    public function showProfil()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $username=$user->getUsername();

        return $this->render('workout/workoutProfil.html.twig', array(
            'namePage' => 'Syssitia App - Profil',
            'nameWorkout' => 'my / '.$username,
            'nav' => '1',
            'footer' => 1,
        ));
    }

}
