<?php
/**
 * Coded by fionera.
 */

namespace Papertowel;

use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Application extends ConsoleApplication
{
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);

        $inputDefinition = $this->getDefinition();
        $inputDefinition->addOption(new InputOption('--domain', '-d', InputOption::VALUE_REQUIRED, 'The Domainname', null));
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->setAutoExit(false);
        $exitCode = parent::run($input, $output);

        $kernel = $this->getKernel();
        if ($kernel instanceof Kernel && $kernel->getDomain() === null) {
            rrmdir($this->getKernel()->getLogDir());
            rrmdir($this->getKernel()->getCacheDir());
        }

        exit($exitCode);
    }
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir, SCANDIR_SORT_NONE);
        foreach ($objects as $object) {
            if ($object !== '.' && $object !== '..') {
                if (is_dir($dir . '/' . $object)) {
                    rrmdir($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }
        }
        rmdir($dir);
    }
}