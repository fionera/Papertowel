<?php

namespace Papertowel\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Papertowel\Models\Website\Website;

/**
 * @ORM\Entity
 * @ORM\Table(name="seo_urls", uniqueConstraints={@UniqueConstraint(name="path", columns={"rewrite_path", "site_id"})})
 * @ORM\Embedded
 */
class SeoURL
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", name="id")
     * @var int
     */protected $id;

    /**
     * @ORM\Column(type="string", name="real_path")
     * @var string
     */
    protected $realPath;

    /**
     * @ORM\Column(type="string", name="rewrite_path")
     * @var string
     */
    protected $rewritePath; //Unique with siteId

    /**
     * @var Website
     * @ORM\Column(type="integer", name="website_id")
     * @ORM\ManyToOne(targetEntity="Website")
     */
    protected $website;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRealPath(): string
    {
        return $this->realPath;
    }

    /**
     * @return string
     */
    public function getRewritePath(): string
    {
        return $this->rewritePath;
    }

    /**
     * @return Website
     */
    public function getWebsite(): Website
    {
        return $this->website;
    }
}