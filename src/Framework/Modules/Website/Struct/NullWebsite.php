<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Website\Struct;

use Doctrine\Common\Collections\Collection;
use Papertowel\Framework\Entity\Plugin\PluginState;
use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Translation\Translation;
use Papertowel\Framework\Entity\Website\Website;

class NullWebsite implements WebsiteInterface
{
    /**
     * @var Language
     */
    private $baseLanguage;
    /**
     * @var Translation
     */
    private $pageTitle;

    /**
     * NullWebsite constructor.
     */
    public function __construct(Language $baseLanguage, Translation $pageTitle)
    {
        $this->baseLanguage = $baseLanguage;
        $this->pageTitle = $pageTitle;
    }


    /**
     * @return integer
     */
    public function getId(): int
    {
        return 0;
    }

    /**
     * @return Website|null
     */
    public function getParent(): ?Website
    {
        return null;
    }

    /**
     * @param Website|null $parent
     */
    public function setParent($parent): void
    {
    }

    /**
     * @return string
     */
    public function getDomain(): ?string
    {
        return null;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain): void
    {
    }

    /**
     * @return string
     */
    public function getThemeName(): string
    {
        return 'Base';
    }

    /**
     * @param string $themeName
     */
    public function setThemeName(string $themeName): void
    {
    }

    /**
     * @return Language[]|Collection
     */
    public function getSupportedLanguages()
    {
        return [];
    }

    /**
     * @param Language[] $supportedLanguages
     */
    public function setSupportedLanguages(array $supportedLanguages): void
    {
    }

    /**
     * @param Language $language
     */
    public function addSupportedLanguage(Language $language): void
    {
    }

    /**
     * @return Translation
     */
    public function getPageTitle(): Translation
    {
        return $this->pageTitle;
    }

    /**
     * @param int $pageTitle
     */
    public function setPageTitle(int $pageTitle): void
    {
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->baseLanguage;
    }

    /**
     * @return PluginState[]
     */
    public function getPluginStates(): Collection
    {
        return [];
    }

    /**
     * @param PluginState $pluginState
     */
    public function addPluginState(PluginState $pluginState): void
    {
    }

    /**
     * @return bool
     */
    public function isBareDomain(): bool
    {
        return true;
    }
}