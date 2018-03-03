<?php

namespace Papertowel\Framework\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="Papertowel\Framework\Repository\Language\TranslationRepository")
 * @ORM\Table(name="translation", uniqueConstraints={@UniqueConstraint(name="translation", columns={"translation_id", "language_id"})})
 */
class Translation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="translation_id")
     * @var int
     */
    private $translationId;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var Language $language
     */
    private $language;

    /**
     * @ORM\Column(type="string", name="translation_string")
     * @var string $translationString
     */
    private $translationString;

    /**
     * Translation constructor.
     * @param $translationId
     * @param Language $language
     * @param string $translationString
     */
    public function __construct(int $translationId, Language $language, string $translationString)
    {
        $this->translationId = $translationId;
        $this->language = $language;
        $this->translationString = $translationString;
    }

    /**
     * @return int
     */
    public function getTranslationId(): int
    {
        return $this->translationId;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getTranslationString(): string
    {
        return $this->translationString;
    }

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }
}
