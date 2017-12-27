<?php
/**
 * Coded by fionera.
 */


namespace App\Components\Theme;


use App\Services\Theme\ThemeProvider;

class CombinedTheme implements ThemeInterface
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
     * @var array
     */
    protected $css = [];

    /**
     * @var array
     */
    protected $javascript = [];

    /**
     * CombinedTheme constructor.
     * @param ThemeInterface|string $theme
     * @param ThemeProvider $themeProvider
     * @throws \LogicException
     */
    public function __construct($theme, ThemeProvider $themeProvider)
    {
        /** @var Theme[] $themes */
        $themes = [];

        $currentTheme = $theme;
        while ($currentTheme !== null) {
            if (!($currentTheme instanceof ThemeInterface) && \is_string($currentTheme)) {
                $currentTheme = $themeProvider->getThemeByName($currentTheme);
            }

            $themes[] = $currentTheme;

            $currentTheme = $currentTheme->getExtend();
        }

        /** @var ThemeInterface[] $reversedThemes */
        $reversedThemes = array_reverse($themes);

        foreach ($reversedThemes as $i => $value) {
            $this->css[] = $value->getCss();
            $this->javascript[] = $value->getJavascript();
        }

        $this->css = array_merge(...$this->css);
        $this->javascript = array_merge(...$this->javascript);

        $themesSize = \count($themes);
        foreach ($themes as $i => $value) {
            $this->name .= $value->getName();
            $this->author .= $value->getAuthor();

            if ($i !== $themesSize - 1) {
                $this->name .= ';';
                $this->author .= ';';
            }
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getExtend(): ?string
    {
        return null;
    }

    public function getCss(): array
    {
        return $this->css;
    }

    public function getJavascript(): array
    {
        return $this->javascript;
    }
}