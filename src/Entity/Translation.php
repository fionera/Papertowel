<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationRepository")
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
     * @ORM\Column(type="string", name="language_id")
     * @ORM\ManyToOne(targetEntity="Language")
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
