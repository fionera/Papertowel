<?php

namespace Papertowel\Models\Page;

use Doctrine\ORM\Mapping as ORM;
use Papertowel\Models\Language;

/**
 * @ORM\Entity
 * @ORM\Table(name="page_content")
 */
class PageContent
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", name="id")
     * @var int
     */protected $id;

    /**
     * @var Language
     * @ORM\Column(type="string",name="language_id")
     * @ORM\ManyToOne(targetEntity="Language")
     */
    protected $language;

    /**
     * @ORM\Column(type="string", name="content")
     * @var string
     */
    protected $content;
}