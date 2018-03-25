<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Controller;

use Papertowel\Framework\Modules\Theme\Struct\ThemeInterface;
use Papertowel\Framework\Modules\Theme\ThemeProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends Controller
{
    /**
     * @var ThemeProvider
     */
    private $themeProvider;
    /**
     * @var ThemeInterface
     */
    private $theme;

    /**
     * FileController constructor.
     * @param ThemeProvider $themeProvider
     * @param ThemeInterface $theme
     */
    public function __construct(ThemeProvider $themeProvider, ThemeInterface $theme)
    {
        $this->themeProvider = $themeProvider;
        $this->theme = $theme;
    }

    /**
     * @Route("/public/{filePath}", name="file", requirements={"filePath"=".+"})
     * @param string $filePath
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction(string $filePath): Response
    {
        if ($this->fileExists($filePath) === null) {
            throw $this->createNotFoundException();
        }

        return $this->render($filePath);
    }

    private function fileExists(string $path): ?string
    {
        $paths = $this->themeProvider->getDependencyNames($this->theme);
        foreach ($paths as $themeName => $templatePath) {
            if (file_exists($templatePath . '/public/' . $path)) {
                return '@' . $themeName .'/' . 'public/' . $path;
            }
        }

        return null;
    }
}
