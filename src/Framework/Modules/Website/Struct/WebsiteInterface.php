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

interface WebsiteInterface
{
    /**
     * @return integer
     */
    public function getId(): int;

    /**
     * @return Website|null
     */
    public function getParent(): ?Website;

    /**
     * @param Website|null $parent
     */
    public function setParent($parent): void;

    /**
     * @return string
     */
    public function getDomain(): ?string;

    /**
     * @param string $domain
     */
    public function setDomain($domain): void;

    /**
     * @return string
     */
    public function getThemeName(): string;

    /**
     * @param string $themeName
     */
    public function setThemeName(string $themeName): void;

    /**
     * @return Language[]|Collection
     */
    public function getSupportedLanguages();

    /**
     * @param Language[] $supportedLanguages
     */
    public function setSupportedLanguages(array $supportedLanguages): void;

    /**
     * @param Language $language
     */
    public function addSupportedLanguage(Language $language): void;

    /**
     * @return Translation
     */
    public function getPageTitle(): Translation;

    /**
     * @param int $pageTitle
     */
    public function setPageTitle(int $pageTitle): void;

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language;

    /**
     * @return PluginState[]
     */
    public function getPluginStates(): Collection;

    /**
     * @param PluginState $pluginState
     */
    public function addPluginState(PluginState $pluginState): void;

    /**
     * @return bool
     */
    public function isBareDomain(): bool;
}