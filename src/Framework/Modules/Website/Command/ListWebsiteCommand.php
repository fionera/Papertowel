<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Website\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Papertowel\Framework\Entity\Translation\Language;
use Papertowel\Framework\Entity\Translation\Translation;
use Papertowel\Framework\Entity\Website\Website;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ListWebsiteCommand extends Command
{
    protected static $defaultName = 'papertowel:website:list';

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->container->get('doctrine');

        /** @var Website[] $websites */
        $websites = $doctrine->getRepository(Website::class)->findAll();

        foreach ($websites as $website) {
            var_dump($website->getPluginStates()->isEmpty());
        }

    }
}