<?php

namespace Papertowel\Models\Menu;

use Doctrine\ORM\Mapping as ORM;
use Papertowel\Models\Translation;
use Papertowel\Models\Website\Website;

/**
 * @ORM\Entity
 * @ORM\Table(name="menus")
 */
class Menu
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", name="id")
     */
    protected $id;

    /**
     * @var Website
     * @ORM\Column(type="integer", name="website_id")
     * @ORM\ManyToOne(targetEntity="Website")
     */
    protected $website;

    /**
     * @var Translation
     * @ORM\Column(type="integer", name="name")
     * @ORM\OneToOne(targetEntity="Translation")
     */
    protected $name;

    /**
     * @var MenuItem[]
     * @ORM\Column(type="array", name="menu_item_ids")
     * @ORM\OneToMany(targetEntity="MenuItem", orphanRemoval=true)
     */
    protected $menuItems;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Website
     */
    public function getWebsite(): Website
    {
        return $this->website;
    }

    /**
     * @return Translation
     */
    public function getName(): Translation
    {
        return $this->name;
    }

    /**
     * @return MenuItem[]
     */
    public function getMenuItems(): array
    {
        return $this->menuItems;
    }
}