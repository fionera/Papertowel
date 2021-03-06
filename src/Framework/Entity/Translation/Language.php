<?php

namespace Papertowel\Framework\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity(repositoryClass="Papertowel\Framework\Repository\Language\LanguageRepository")
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int $id
     */
    private $id;

    /**
     * @Column(type="string", name="language_string", unique=true, nullable=false)
     * @var string $languageString
     */
    protected $languageString;

    /**
     * @ORM\OneToOne(targetEntity="Translation", mappedBy="translationId")
     * @ORM\JoinColumns(value={@ORM\JoinColumn(name="translation_id", referencedColumnName="translation_id"), @ORM\JoinColumn(name="id", referencedColumnName="language_id")})
     * @var Translation
     */
    private $name;

    /**
     * Language constructor.
     * @param string $languageString
     * @param Translation $name
     */
    public function __construct(string $languageString, ?Translation $name)
    {
        $this->languageString = $languageString;
        $this->name = $name;
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
     * @return Translation
     */
    public function getName(): Translation
    {
        return $this->name;
    }

    /**
     * @param Translation $name
     */
    public function setName(Translation $name): void
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->getName()->getTranslationString() . ' (' . $this->getLanguageString() . ')';
    }
}
