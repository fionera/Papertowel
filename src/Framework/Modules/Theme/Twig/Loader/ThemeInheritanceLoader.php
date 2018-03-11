<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Theme\Twig\Loader;

use Papertowel\Framework\Modules\Theme\Struct\ThemeInterface;
use Twig\Loader\FilesystemLoader;

class ThemeInheritanceLoader extends FilesystemLoader
{
    /**
     * @var ThemeInterface
     */
    private $themeInterface;

    public function __construct( ThemeInterface $themeInterface, $paths = array(), ?string $rootPath = null)
    {
        parent::__construct($paths, $rootPath);
        $this->themeInterface = $themeInterface;
    }


    protected function findTemplate($name, $throw = true)
    {
        if (substr_count($name, '@parent') === 1) {
            $name = str_replace('@parent', '@' . $this->themeInterface->getName(), $name);
        }

        return parent::findTemplate($name, $throw);
    }

    public function getAllPaths()
    {
        return $this->paths;
    }
}