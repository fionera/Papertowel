<?php

namespace App\Controller;

use App\Component\Theme\Theme;
use App\Component\Theme\ThemeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/staticPage")
 */
class StaticPageController extends Controller
{
    /**
     * @Route("/{id}", name="staticPage_index")
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \LogicException
     */
    public function indexAction($id)
    {

        //die(print_r($this->container->get('twig')->getLoader()->getPaths('__main__')));
        return $this->render('frontend/index/index.html.twig', array(
            'number' => $id,
        ));
    }
}