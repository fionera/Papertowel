<?php
/**
 * Coded by fionera.
 */

namespace Papertowel;

use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpKernel\KernelInterface;

class Application extends ConsoleApplication
{
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);

        $inputDefinition = $this->getDefinition();
        $inputDefinition->addOption(new InputOption('--domain', '-d', InputOption::VALUE_REQUIRED, 'The Domainname', null));
    }
}