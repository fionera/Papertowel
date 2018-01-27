<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebsiteRepository")
 * @ORM\Table(name="website", uniqueConstraints={@UniqueConstraint(name="parent_domain", columns={"parent_id", "domain"})})
 */
class Website
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="parent_id", nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Website")
     * @var Website|null $parent
     */
    private $parent;

    /**
     * @ORM\Column(type="string", name="domain")
     * @var string $domain
     */
    private $domain;

    /**
     * @ORM\Column(type="simple_array", name="supported_languages")
     * @var Language[]
     */
    private $supportedLanguages; //TODO: Get LanguageObjects by id array

    /**
     * @var Language $defaultLanguage
     * @ORM\Column(type="integer", name="default_language_id")
     * @ORM\ManyToOne(targetEntity="App\Entity\Translation")
     */
    private $defaultLanguage;

    /**
     * @ORM\Column(type="string", name="theme_name")
     * @var string $themeName
     */
    private $themeName;

    /**
     * @ORM\Column(type="integer", name="translation_id")
     * @var int
     */
    private $pageTitle;//TODO: Get the correct Translation Object

    /**
     * Website constructor.
     * @param string $domain
     * @param string $themeName
     * @param int $pageTitle
     * @param Language $defaultLanguage
     * @param Language[]|null $supportedLanguages
     * @param Website|null $parent
     */
    public function __construct(string $domain, string $themeName, int $pageTitle, Language $defaultLanguage, array $supportedLanguages = [], $parent = null)
    {
        $this->domain = $domain;
        $this->parent = $parent;
        $this->themeName = $themeName;
        $this->pageTitle = $pageTitle;
        $this->defaultLanguage = $defaultLanguage;
        $this->supportedLanguages = $supportedLanguages;
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Website|null
     */
    public function getParent(): ?Website
    {
        return $this->parent;
    }

    /**
     * @param Website|null $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getThemeName(): string
    {
        return $this->themeName;
    }

    /**
     * @param string $themeName
     */
    public function setThemeName(string $themeName): void
    {
        $this->themeName = $themeName;
    }

    /**
     * @return Language[]
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }

    /**
     * @param Language[] $supportedLanguages
     */
    public function setSupportedLanguages(array $supportedLanguages): void
    {
        $this->supportedLanguages = $supportedLanguages;
    }

    /**
     * @param Language $language
     */
    public function addSupportedLanguage(Language $language): void
    {
        $this->supportedLanguages[] = $language;
    }

    /**
     * @return int
     */
    public function getPageTitle(): int
    {
        return $this->pageTitle;
    }

    /**
     * @param int $pageTitle
     */
    public function setPageTitle(int $pageTitle): void
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    /**
     * @return bool
     */
    public function isBareDomain(): bool
    {
        return $this->parent === null;
    }
}
