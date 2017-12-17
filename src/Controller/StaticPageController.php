<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/staticPage")
 */
class StaticPageController extends Controller
{
    /**
     * @Route("/{id}", name="staticPage_index")
     */
    public function indexAction($id)
    {
        return $this->render('lucky/number.html.twig', array(
            'number' => $id,
        ));
    }
}