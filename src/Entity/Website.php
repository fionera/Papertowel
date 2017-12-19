<?php

namespace App\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
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
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Website|null
     */
    public function getParent() : ?Website
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
    public function setThemeName(string $themeName)
    {
        $this->themeName = $themeName;
    }

    /**
     * @return bool
     */
    public function isBareDomain() : bool
    {
        return $this->parent === null;
    }
}
