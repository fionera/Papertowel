<?php

namespace Papertowel\Framework\Entity\Website;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Papertowel\Framework\Entity\Plugin\PluginState;
use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Translation\Translation;

/**
 * @ORM\Entity(repositoryClass="Papertowel\Framework\Repository\Website\WebsiteRepository")
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
     * @ORM\OneToOne(targetEntity="Website")
     * @var Website|null $parent
     */
    private $parent;

    /**
     * @ORM\Column(type="string", name="domain")
     * @var string $domain
     */
    private $domain;

    /**
     * @ORM\ManyToMany(targetEntity="Papertowel\Framework\Entity\Translation\Language")
     * @ORM\JoinTable(name="supported_languages",
     *      joinColumns={@ORM\JoinColumn(name="website_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="language_id", referencedColumnName="id", unique=true)}
     *      )
     * @var Language[]|Collection
     */
    private $supportedLanguages;

    /**
     * @ORM\OneToOne(targetEntity="Papertowel\Framework\Entity\Translation\Language")
     * @var Language $defaultLanguage
     */
    private $defaultLanguage;

    /**
     * @ORM\Column(type="string", name="theme_name")
     * @var string $themeName
     */
    private $themeName;

    /**
     * @ORM\OneToOne(targetEntity="Papertowel\Framework\Entity\Translation\Translation")
     * @ORM\JoinColumns(value={@ORM\JoinColumn(name="translation_id", referencedColumnName="translation_id"), @ORM\JoinColumn(name="default_language_id", referencedColumnName="language_id")})
     * @var int
     */
    private $pageTitle;

    /**
     * @ORM\OneToMany(targetEntity="Papertowel\Framework\Entity\Plugin\PluginState", mappedBy="website")
     * @ORM\JoinColumn(name="id", referencedColumnName="website_id", nullable=true)
     * @var PluginState[]
     */
    private $pluginStates;

    /**
     * Website constructor.
     * @param string $domain
     * @param string $themeName
     * @param Translation $pageTitle
     * @param Language $defaultLanguage
     * @param Language[]|null $supportedLanguages
     * @param Website|null $parent
     * @param PluginState[]|null $pluginStates
     */
    public function __construct(string $domain, string $themeName, Translation $pageTitle, Language $defaultLanguage, Website $parent = null, $supportedLanguages = null, $pluginStates = null)
    {
        $this->domain = $domain;
        $this->parent = $parent;
        $this->themeName = $themeName;
        $this->pageTitle = $pageTitle;
        $this->defaultLanguage = $defaultLanguage;
        $this->supportedLanguages = $supportedLanguages ?? [$defaultLanguage];
        $this->pluginStates = $pluginStates ?? new ArrayCollection();
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
     * @return Language[]|Collection
     */
    public function getSupportedLanguages()
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
     * @return PluginState[]
     */
    public function getPluginStates(): Collection
    {
        return $this->pluginStates;
    }

    /**
     * @return bool
     */
    public function isBareDomain(): bool
    {
        return $this->parent === null;
    }
}
