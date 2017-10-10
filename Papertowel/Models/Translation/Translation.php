<?php

namespace Papertowel\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="translations")
 */
class Translation
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", name="id")
     * @var int
     */
    protected $id;

    /**
     * @var Language
     * @ORM\Column(type="string",name="language_id")
     * @ORM\ManyToOne(targetEntity="Language")
     */
    protected $language;

    /**
     * @ORM\Column(type="string", name="translation")
     * @var string
     */
    protected $translation;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getTranslation(): string
    {
        return $this->translation;
    }
}