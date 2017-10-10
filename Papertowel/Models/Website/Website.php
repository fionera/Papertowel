<?php

namespace Papertowel\Models\Website;

use Doctrine\ORM\Mapping as ORM;
use Papertowel\Models\Translation;

/**
 * @ORM\Entity
 * @ORM\Table(name="websites")
 */
class Website
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(type="string",name="domain")
     * @var string
     */
    protected $domain;

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
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }
}