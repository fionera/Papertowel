<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin\Command;

use Doctrine\ORM\EntityManagerInterface;
use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Plugin\PluginManager;
use Papertowel\Framework\Modules\Plugin\PluginProvider;
use Papertowel\Framework\Modules\Website\WebsiteProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PluginEnableCommand extends Command
{
    protected static $defaultName = 'papertowel:plugin:enable';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var WebsiteProvider
     */
    private $websiteProvider;
    /**
     * @var PluginProvider
     */
    private $pluginProvider;
    /**
     * @var PluginManager
     */
    private $pluginManager;

    public function __construct(EntityManagerInterface $entityManager, WebsiteProvider $websiteProvider, PluginProvider $pluginProvider, PluginManager $pluginManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->websiteProvider = $websiteProvider;
        $this->pluginProvider = $pluginProvider;
        $this->pluginManager = $pluginManager;
    }


    protected function configure()
    {
        $this->addArgument('websiteHost')->addArgument('pluginName');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $websiteHost = $input->getArgument('websiteHost');
        $pluginName = $input->getArgument('pluginName');

        /** @var Website|null $website */
        $website = $this->websiteProvider->getWebsiteByHost($websiteHost);

        if ($website === null) {
            throw new \Exception('Website not found');
        }

        $existingPlugins = $this->pluginProvider->getPluginNames();

        if (isset($existingPlugins[$pluginName])) {
            throw new \Exception('Plugin not found');
        }

        if (!isset($this->pluginProvider->getPluginList()[$pluginName])) {
            $this->pluginProvider->loadPlugin($pluginName);
        }

        $plugin = $this->pluginProvider->getPlugin($pluginName);

        if ($plugin === null) {
            throw new \Exception('Plugin not found');
        }

        $this->pluginManager->enablePlugin($plugin, $website);
    }
}