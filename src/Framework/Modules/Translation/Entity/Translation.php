<?php

namespace Papertowel\Framework\Modules\Translation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="Papertowel\Framework\Modules\Translation\Repository\TranslationRepository")
 * @ORM\Table(name="translation", uniqueConstraints={@UniqueConstraint(name="translation", columns={"translation_id", "language_id"})})
 */
class Translation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="translation_id")
     */
    private $translationId;

    /**
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var string $language
     */
    private $language;

    /**
     * @ORM\Column(type="string", name="translation_string")
     * @var string $translationString
     */
    private $translationString;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTranslationId()
    {
        return $this->translationId;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
