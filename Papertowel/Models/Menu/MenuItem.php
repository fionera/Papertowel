<?php

namespace Papertowel\Models\Menu;

use Doctrine\ORM\Mapping as ORM;
use Papertowel\Models\Translation;

/**
 * @ORM\Entity
 * @ORM\Table(name="menu_items")
 */
class MenuItem
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", name="id")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="type")
     */
    protected $type;

    /**
     * @var Translation
     * @ORM\Column(type="integer", name="name")
     * @ORM\OneToOne(targetEntity="Translation")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", name="content")
     */
    protected $content;

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
    public function getType(): string
    {
        return $this->type;
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
    public function getContent(): string
    {
        return $this->content;
    }
}