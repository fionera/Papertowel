<?php
/**
 * Created by IntelliJ IDEA.
 * User: fionera
 * Date: 22.06.17
 * Time: 22:24
 */

namespace Papertowel;

use Papertowel\Core\Config;
use Papertowel\Core\Database;
use Papertowel\Core\Router;
use Papertowel\Core\View;
use Papertowel\Core\Session;

/**
 * The Main Instance of the Website
 * Class Papertowel
 * @package Papertowel
 */
class Papertowel
{
    /**
     * @var Database $database
     */
    private $database;

    /**
     * @var Session $session
     */
    private $session;

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var Router $router
     */
    private $router;

    /**
     * @var View $view
     */
    private $view;

    /**
     * Papertowel constructor.
     * It starts the Session and creates the Instances.
     */
    public function __construct()
    {
        $this->database = new Database();
        $this->session = new Session();
        $this->config = new Config();
        $this->router = new Router();
        $this->view = new View();
    }

    /**
     * @return Config
     */
    public function Config(): Config
    {
        return $this->config;
    }

    /**
     * @return Database
     */
    public function Database(): Database
    {
        return $this->database;
    }

    /**
     * @return Router
     */
    public function Router(): Router
    {
        return $this->router;
    }

    /**
     * @return Session
     */
    public function Session(): Session
    {
        return $this->session;
    }

    /**
     * @return View
     */
    public function View(): View
    {
        return $this->view;
    }

}