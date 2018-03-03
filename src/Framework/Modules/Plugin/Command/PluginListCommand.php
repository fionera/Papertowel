<?php
/**
 * Coded by fionera.
 */

namespace Papertowel\Framework\Modules\Plugin\Command;

use Papertowel\Framework\Entity\Website\Website;
use Papertowel\Framework\Modules\Plugin\PluginProvider;
use Papertowel\Framework\Modules\Website\Struct\NullWebsite;
use Papertowel\Framework\Modules\Website\Struct\WebsiteInterface;
use Papertowel\Papertowel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PluginListCommand extends Command
{
    protected static $defaultName = 'papertowel:plugin:list';

    /**
     * @var Website
     */
    private $website;
    /**
     * @var PluginProvider
     */
    private $pluginProvider;

    public function __construct(WebsiteInterface $website, PluginProvider $pluginProvider)
    {
        parent::__construct();

        $this->website = $website;
        $this->pluginProvider = $pluginProvider;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($this->website instanceof NullWebsite) {
            $output->writeln('<error>Missing Website. If you want to display all Plugins for all Websites please add -f</error>');
            //TODO: Add Support for all Websites
        }

        $table = new Table($output);

        /**
         * Plugins in the Plugin Folder
         */
        $pluginNames = $this->pluginProvider->getPluginNames();

        /**
         * Loaded Plugins
         */
        $pluginList = $this->pluginProvider->getPluginList();

        $table->setHeaders([
           'Name',
           'Installed',
            'Enabled'
        ]);

        $rows = [];

        foreach ($pluginNames as $pluginName){
            if (!isset($pluginList[$pluginName])) {
                $rows[] = [
                    $pluginName,
                    $this->stateString(false),
                    $this->stateString(false),
                ];
            } else {
                $rows[] = [
                    $pluginList[$pluginName]->getName(),
                    $this->stateString(true),
                    $this->stateString($pluginList[$pluginName]->isEnabled()),
                ];
            }
        }

        $table->setRows($rows);
        $table->render();
    }

    private function stateString(bool $state): string
    {
        return $state ? 'Yes' : 'No';
    }
}
