<?php

namespace Papertowel\View;

use Papertowel\Core\Config;
use Papertowel\Core\Database;
use Papertowel\Models\Customer;
use Papertowel\Models\Site;
use Papertowel\Models\SiteContent;
use Papertowel\Request\Request;
use Papertowel\Utils\RequestUtils;

class MenuBuilder
{

    /** @var Config $config */
    private $config;

    /** @var Request $request */
    private $request;

    /**
     * MenuBuilder constructor.
     * @param Config $config
     * @param Request $request
     */
    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    public function getMenuContent()
    {
        /** @var array $menu */
        $menu = $this->config->get('menu');

        $menuHTML = '';
        foreach ($menu as $key => $value) {

            if ($value !== null && is_array($value)) {
                $menuHTML .= $this->getSubMenuHTML($key, $value);
            } else {
                $menuHTML .= $this->getMenuEntryHTML($this->getTitleForURL($value), $value);
            }
        }

        return $menuHTML;
    }

    /**
     * @param $url
     * @return null|string
     */
    private function getTitleForURL($url)
    {
        $siteContent = RequestUtils::getSiteContentForURL($url, $this->request->getLanguage(), $this->request->getCustomer());

        if ($siteContent !== null) {
            return $siteContent->getSiteTitle();
        }

        return null;
    }

    /**
     * @param string $topURL
     * @param array $subMenu
     * @return string
     */
    private function getSubMenuHTML($topURL, $subMenu)
    {
        $subMenuEntries = $this->getHTMLForArray($subMenu);

        switch ($this->config->get('template')['sidebar']) {
            case 'top':
                return '<li class="dropdown"><a href="' . $topURL . '" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="100"
                       data-close-others="true" role="button" aria-haspopup="true"
                       aria-expanded="false">' .
                    $this->getTitleForURL($topURL) .
                    '<b class="caret"></b></a><ul class="dropdown-menu">' .
                    implode('', $subMenuEntries) .
                    '</ul></li>';
                break;

            case 'left':
                return '<li class="dropdown"><a href="' . $topURL . '" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="100"
                       data-close-others="true" role="button" aria-haspopup="true"
                       aria-expanded="false">' .
                    $this->getTitleForURL($topURL) .
                    '<b class="caret"></b></a><ul class="dropdown-menu">' .
                    implode('', $subMenuEntries) .
                    '</ul></li>';
                break;

            default:
                return '';
        }
    }

    /**
     * @param string $title
     * @param string $url
     * @return string
     */
    private function getMenuEntryHTML($title, $url): string
    {
        switch ($this->config->get('template')['navbar']) {
            case 'top':
                return '<li class="' . ($this->request->getSite()->getUrl() === $url ? 'active' : '') . '"><a href="' . $url . '">' . $title . '</a></li>';
                break;

            case 'left':
                $classes = '';

                if ($this->request->getSite()->getUrl() === $url) {
                    $classes .= 'fa fa-arrow-right';
                }


                return '<li class="' . ($this->request->getSite()->getUrl() === $url ? 'active' : '') . '"> <a href="' . $url . '" data-scroll="" class=""><span class="' . $classes . '">' . $title . '</span></a></li>';
                break;

            default:
                return '';
                break;
        }
    }

    /**
     * @param array $array
     * @return array
     */
    private function getHTMLForArray($array)
    {
        $subMenuEntrys = [];
        foreach ($array as $subMenuEntry => $subSubMenu) {
            $menuEntryTitle = $this->getTitleForURL($subMenuEntry);

            if ($menuEntryTitle === null) {
                continue;
            }

            $subMenuEntrys[] = $this->getMenuEntryHTML($menuEntryTitle, $subMenuEntry);
        }

        return $subMenuEntrys;
    }
}