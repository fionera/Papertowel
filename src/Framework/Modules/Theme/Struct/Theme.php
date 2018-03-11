<?php

namespace Papertowel\Framework\Modules\Theme\Struct;

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
     * @var string
     */
    protected $dependency = '';

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
     * @return string[]
     */
    public function getDependency(): string
    {
        return $this->dependency;
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
