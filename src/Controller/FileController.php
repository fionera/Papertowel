<?php
/**
 * Coded by fionera.
 */

namespace App\Controller;

use App\Service\Theme\ThemeProvider;
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
     * FileController constructor.
     * @param ThemeProvider $themeProvider
     */
    public function __construct(ThemeProvider $themeProvider)
    {
        $this->themeProvider = $themeProvider;
    }

    /**
     * @Route("/public/{filePath}", name="file", requirements={"filePath"=".+"})
     * @param string $filePath
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction(string $filePath): Response
    {
        /** @var string $filePath */
        $filePath = $this->fileExists($filePath);

        if ($filePath === '') {
            throw $this->createNotFoundException();
        }

        return $this->render($filePath);
    }

    private function fileExists(string $path): string
    {
        $paths = $this->themeProvider->getDependencyNamespaces($this->themeProvider->getThemeForCurrentRequest());
        foreach ($paths as $themeName => $templatePath) {
            if (file_exists($templatePath . '/public/' . $path)) {
                return '@' . $themeName .'/' . 'public/' . $path;
            }
        }

        return '';
    }
}