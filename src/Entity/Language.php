<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LanguageRepository")
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @Column(type="string", name="language_string", unique=true, nullable=false)
     * @var string $languageString
     */
    protected $languageString;

    /**
     * @ORM\Column(type="integer", name="translation_id")
     * @ORM\ManyToOne(targetEntity="App\Entity\Translation")
     * @var Translation
     */
    protected $translation; //TODO: Get the correct Translation Object
}
