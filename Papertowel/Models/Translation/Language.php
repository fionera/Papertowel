<?php

namespace Papertowel\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * Class Language
 * @package Papertowel\Models
 * @ORM\Entity()
 */
class Language {
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string", name="language_string")
     * @var string
     */
    protected $languageString;

    /**
     * @Column(type="string", name="language_name")
     * @var string
     */
    protected $languageName;

    /**
     * Language constructor.
     * @param string $languageString
     * @param string $languageName
     */
    public function __construct($languageString, $languageName)
    {
        $this->languageString = $languageString;
        $this->languageName = $languageName;
    }

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
    public function getLanguageString(): string
    {
        return $this->languageString;
    }

    /**
     * @return string
     */
    public function getLanguageName(): string
    {
        return $this->languageName;
    }
}