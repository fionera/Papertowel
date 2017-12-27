<?php

namespace App\Components\Theme;

abstract class Theme implements ThemeInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var null|string
     */
    protected $author;

    /**
     * @var null|string
     */
    protected $extend;

    /**
     * @var array
     */
    protected $css = [];

    /**
     * @var array
     */
    protected $javascript = [];

    /**
     * Theme constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @return null|string
     */
    public function getExtend(): ?string
    {
        return $this->extend;
    }

    /**
     * @return array
     */
    public function getCss(): array
    {
        return $this->css;
    }

    /**
     * @return array
     */
    public function getJavascript(): array
    {
        return $this->javascript;
    }
}