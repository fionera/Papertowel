<?php

namespace Papertowel\Models\Website;

use Doctrine\ORM\Mapping as ORM;
use Papertowel\Models\Translation;

/**
 * @ORM\Entity
 * @ORM\Table(name="website_subdomains")
 */
class SubSite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", name="id")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="translation_id")
     * @ORM\OneToOne(targetEntity="Translation")
     * @var Translation
     */
    protected $name;

    /**
     * @ORM\Column(type="string", name="sub_domain")
     * @var string
     */
    protected $subDomain;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Translation
     */
    public function getName(): Translation
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSubDomain(): string
    {
        return $this->subDomain;
    }
}