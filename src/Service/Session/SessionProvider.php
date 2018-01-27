<?php
/**
 * Coded by fionera.
 */

namespace App\Service\Session;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionProvider
{
    /**
     * @var RequestStack
     */
    private $requestStack;


    /**
     * LanguageProvider constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getSession() : SessionInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            throw new \LogicException('CurrentRequest should not be null');
        }

        if (!$request->hasSession()) {
            $request->setSession(new Session());
        }

        return $request->getSession();
    }
}