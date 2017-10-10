<?php
/**
 * Created by IntelliJ IDEA.
 * User: fionera
 * Date: 22.06.17
 * Time: 22:25
 */

namespace Papertowel\Core;

use Papertowel\Request\Request;

/**
 * Parses the Request and initiates the Rendering
 * Class Router
 * @package Papertowel
 */
class Router
{
    /**
     * @param string $parameter
     * @return null|string
     */
    public function getParameter($parameter): ?string
    {
        if (isset($_GET[$parameter])) {
            return $_GET[$parameter];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->extract_domain($_SERVER['HTTP_HOST']);
    }

    /**
     * @return string|null
     */
    public function getSubDomain(): ?string
    {
        return $this->extract_subdomains($_SERVER['HTTP_HOST']);
    }


    public function handle(Request $request)
    {
        Papertowel()->View()->assignRequestVars($request);

        Papertowel()->View()->renderWebsiteContent($request->getRequestedSiteContent());
    }

    /**
     * @param $domain string
     * @return string
     */
    private function extract_domain($domain)
    {
        if (preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches)) {
            return $matches['domain'];
        }

        return $domain;
    }

    /**
     * @param $fullDomain string
     * @return string|null
     */
    private function extract_subdomains($fullDomain)
    {
        $domain = $this->extract_domain($fullDomain);

        return rtrim(strstr($fullDomain, $domain, true), '.');
    }
}