<?php

namespace Papertowel\Utils;

use Papertowel\Core\View;
use Papertowel\Models\Customer;
use Papertowel\Models\Language;
use Papertowel\Models\Site;
use Papertowel\Models\SiteContent;
use Papertowel\Papertowel;

class RequestUtils
{

    private static $contentCache = [];
    private static $siteCache = [];

    /**
     * @param Site $site
     * @param Language $language
     * @param Customer|null $customer
     * @return SiteContent
     */
    public static function getSiteContentForSite($site, $language, $customer = null): SiteContent
    {
        $md5 = md5($site->getId() . $language->getId() . ($customer !== null ? $customer->getId() : 0));
        if (isset(self::$contentCache[$md5])) {
            return self::$contentCache[$md5];
        }

        $siteContentRepository = Papertowel()->Database()->getRepository(SiteContent::class);

        if ($siteContentRepository->findOneBy(['siteId' => $site->getId()]) === null) {
            self::$contentCache[$md5] = $siteContentRepository->findOneBy(['siteId' => View::NOT_FOUND_ID, 'languageId' => $language->getId()]);

            return self::$contentCache[$md5];
        }

        if ($customer !== null) {
            //Try direct Access
            $allFactors = $siteContentRepository->findOneBy([
                'siteId' => $site->getId(),
                'languageId' => $language->getId(),
                'customerId' => $customer->getId(),
            ]);

            if ($allFactors !== null) {
                self::$contentCache[$md5] = $allFactors;
                return self::$contentCache[$md5];
            }
        }

        //Try without customer
        $notCustom = $siteContentRepository->findOneBy([
            'siteId' => $site->getId(),
            'languageId' => $language->getId(),
            'customerId' => 0,
        ]);

        if ($notCustom !== null) {
            self::$contentCache[$md5] = $notCustom;
            return self::$contentCache[$md5];
        }

        self::$contentCache[$md5] = $siteContentRepository->findOneBy(['siteId' => View::NOT_FOUND_ID, 'languageId' => $language->getId()]); // Just for Security

        return self::$contentCache[$md5];
    }

    /**
     * @param string $url
     * @param Language $language
     * @param Customer|null $customer
     * @return SiteContent|null
     */
    public static function getSiteContentForURL($url, $language, $customer = null) : ?SiteContent
    {
        $md5 = md5($url . $language->getId() . ($customer !== null ? $customer->getId() : 0));
        if (isset(self::$contentCache[$md5])) {
            return self::$contentCache[$md5];
        }

        $siteRepository = Papertowel()->Database()->getRepository(Site::class);

        /** @var Site $site */
        $site = $siteRepository->findOneBy(['url' => $url]);

        if ($site === null) {
            return null;
        }

        return self::getSiteContentForSite($site, $language, $customer);
    }
}