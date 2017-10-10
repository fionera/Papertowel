<?php

namespace Papertowel\Models\Page;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class Page {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @ORM\ManyToOne(targetEntity="PageContent")
     * @var PageContent
     */
    protected $pageContent;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return PageContent
     */
    public function getPageContent(): PageContent
    {
        return $this->pageContent;
    }
}