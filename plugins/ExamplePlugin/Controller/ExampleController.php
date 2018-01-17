<?php
/**
 * Coded by fionera.
 */

namespace plugins\ExamplePlugin\Controller;

use App\Component\Plugin\Controller\PluginController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ExampleController
 * @Route(name="/example")
 */
class ExampleController extends PluginController
{
    /**
     * @Route(path="/example")
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('@ExamplePlugin/frontend/example/index.html.twig');
    }
}