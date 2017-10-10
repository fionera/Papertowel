<?php
/**
 * Created by IntelliJ IDEA.
 * User: fionera
 * Date: 22.06.17
 * Time: 22:25
 */

namespace Papertowel\Core;

use Papertowel\Config\Languages;
use Papertowel\Models\Customer;
use Papertowel\Models\Language;
use Papertowel\Models\Site;
use Papertowel\Models\SiteContent;
use Papertowel\Papertowel;
use Papertowel\Request\Request;
use Papertowel\View\MenuBuilder;
use Papertowel\View\TemplateBlockResource;
use Smarty;
use Smarty_Resource_Mysql;
use SmartyException;

class View
{

    const NOT_FOUND_ID = 404;

    /** @var \Smarty $smarty The Smarty Instance */
    protected $smarty;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->smarty = new Smarty(); // Create a new Smarty Instance

        //$smarty->force_compile = true;
        $this->smarty->debugging = false;
        $this->smarty->caching = false;
        $this->smarty->cache_lifetime = 120;

        $this->smarty->registerResource('db', new TemplateBlockResource());
    }

    public function renderWebsiteContent(SiteContent $siteContent)
    {
        $this->smarty->display('string:' . $siteContent->getContent());
    }

    public function renderErrorPage(int $errorCode, string $errorMessage = null)
    {
        $this->smarty->display('string:<h2>Sorry an Error occurred </h2><br><h3>Errorcode: ' . $errorCode . ' - ' . $errorMessage. '</h3>');//TODO
    }

    public function assign($key, $value)
    {
        $this->smarty->assign($key, $value);
    }

    public function assignRequestVars(Request $request)
    {
        if ($request->isCustomerPage()) {
            $this->assignCustomerVars($request->getCustomer());
        }


        $this->assignLanguageList($request->getLanguage()->getLanguageString());

        $this->assignTemplateVars($request, Papertowel()->Config());

        $this->assignSiteVars($request->getRequestedSiteContent());
    }

    private function assignSiteVars(SiteContent $siteContent) {
        $this->smarty->assign('site', [
           'siteTitle' => $siteContent->getSiteTitle(),
            'domain' => Papertowel()->Router()->getURL()['domain']
        ]);
    }

    private function assignTemplateVars(Request $request, Config $config)
    {
        $menuBuilder = new MenuBuilder(Papertowel()->Config(), $request);

        $templateVars = $config->get('template');
        $templateVars['menuBuilder'] = $menuBuilder->getMenuContent();

        $this->smarty->assign('template', $templateVars);
    }

    private function assignCustomerVars(Customer $customer)
    {
        $this->smarty->assign('customer', [
            'name' => $customer->getName(),
            'subdomain' => $customer->getSubdomain()
        ]);
    }

    private function assignLanguageList(string $selected_language)
    {
        /** @var Language[] $languageList */
        $languageList = Papertowel()->Database()->getRepository(Language::class)->findAll();

        $languages = [];
        foreach ($languageList as $language) {
            $languages[$language->getLanguageString()] = $language->getLanguageName();
        }

        $this->smarty->assign('language', [
            'languages' => $languages,
            'selected' => $selected_language
        ]);
    }
}