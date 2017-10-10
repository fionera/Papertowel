<?php

namespace Papertowel\Request;

use Papertowel\Core\Session;
use Papertowel\Core\View;
use Papertowel\Models\Customer;
use Papertowel\Models\Site;
use Papertowel\Models\SiteContent;
use Papertowel\Utils\LanguageUtils;
use Papertowel\Models\Language;
use Papertowel\Utils\RequestUtils;

class Request
{
    const LANGUAGE_PARAMETER = 'language';

    /** @var Language $language */
    private $language;

    /** @var Customer $customer */
    private $customer;

    /** @var Site $site */
    private $site;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->language = $this->getRequestedLanguage();

        $this->customer = $this->getRequestedCustomer();

        $this->site = $this->getRequestedSite();
    }

    /**
     * @return SiteContent
     */
    public function getRequestedSiteContent(): SiteContent
    {
        return RequestUtils::getSiteContentForSite($this->site, $this->language, $this->customer);
    }

    /**
     * @return Language
     */
    private function getRequestedLanguage(): Language
    {
        if (isset($_REQUEST[self::LANGUAGE_PARAMETER]) === true) {
            Papertowel()->Session()->set(Session::LANGUAGE, $_REQUEST[self::LANGUAGE_PARAMETER]);
        }

        $sessionLanguage = Papertowel()->Session()->get(Session::LANGUAGE);

        if ($sessionLanguage !== null && LanguageUtils::isValidLanguageString($sessionLanguage)) {
            return LanguageUtils::LanguageObjectByStringFromDb($sessionLanguage);
        }

        return LanguageUtils::getDefaultLanguageObject(); //TODO: Show Error when null
    }

    /**
     * @return Site
     */
    private function getRequestedSite(): Site
    {
        $site = Papertowel()->Database()->getRepository(Site::class)
            ->findOneBy(['url' => Papertowel()->Router()->getURL()['path']]);

        if ($site === null) {
            $site = Papertowel()->Database()->getRepository(Site::class)
                ->findOneBy(['id' => 0]);
        }

        return $site;
    }

    private function getRequestedCustomer(): Customer
    {
        $subdomainArray = Papertowel()->Router()->getURL()['sub'];
        $customerRepository = Papertowel()->Database()->getRepository(Customer::class);

        if (count($subdomainArray) > 0 && !isset(Papertowel()->Config()->get('blacklisted_subdomains')[$subdomainArray[0]])) {
            $requestedCustomer = $customerRepository->findOneBy(['subdomain' => implode('.', array_reverse($subdomainArray))]);
            if ($requestedCustomer !== null) {
                return $requestedCustomer;
            }
        }

        return $customerRepository->findOneBy(['id' => 0]);
    }

    public function isCustomerPage(): bool
    {
        return $this->customer !== null || $this->customer->getId() !== 0;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @return Site
     */
    public function getSite(): Site
    {
        return $this->site;
    }
}