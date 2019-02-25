<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function defaultAction()
    {
        return $this->redirect($this->generateUrl('booking_custom_schedule'));
    }

}
