<?php

namespace Papertowel;

use Papertowel\Framework\Modules\Plugin\PluginList;
use Papertowel\Framework\Modules\Plugin\PluginProvider;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    /**
     * @var Request
     */
    private $request;

    use MicroKernelTrait;

    public const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @var PluginList
     */
    private $plugins;

    /**
     * @var PluginProvider
     */
    private $pluginProvider;

    /**
     * Kernel constructor.
     * @param string $environment
     * @param bool $debug
     */
    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);

        $this->plugins = new PluginList();
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->getProjectDir() . '/var/log';
    }

    /**
     * @param Request $request
     * @param int $type
     * @param bool $catch
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $this->request = $request;

        return parent::handle($request, $type, $catch);
    }

    /**
     * @return \Generator|\Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles()
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        $contents = array_merge($contents, []);

        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }

        foreach ($this->getPlugins() as $plugin) {
            yield $plugin;
        }
    }

    /**
     *
     * @throws \RuntimeException
     */
    private function getPlugins() : array
    {
        $connection = DatabaseConnector::createPdoConnection();

        $enabledPluginsPrepared = $connection->prepare('SELECT plugin.name FROM plugin_state
INNER JOIN plugin ON(plugin.id = plugin_state.plugin_id)
WHERE website_id = (SELECT id FROM website WHERE `domain` = ?) AND plugin_state.enabled = 1 AND plugin_state.installed = 1');
        $enabledPluginsPrepared->execute([$this->request->getHost()]);
        $enabledPlugins = $enabledPluginsPrepared->fetchAll(\PDO::FETCH_COLUMN);

        $this->pluginProvider = new PluginProvider($this->getProjectDir() . '/plugins');
        $this->pluginProvider->loadPlugins($enabledPlugins);
        $loadedPlugins = $this->pluginProvider->getPluginList();

        foreach ($enabledPlugins as $pluginName) {
            if (!array_key_exists($pluginName, $loadedPlugins)) {
                throw new \RuntimeException('Missing Plugin: ' . $pluginName);
            }
        }

        return $loadedPlugins;
    }

    protected function initializeContainer()
    {
        parent::initializeContainer();
        $this->container->set('papertowel.framework.plugin.plugin_provider', $this->pluginProvider);

        foreach ($this->plugins->getActivePlugins() as $plugin) {
            $plugin->setContainer($this->container);
        }
    }


    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir() . '/config';
        $loader->load($confDir . '/packages/*' . self::CONFIG_EXTS, 'glob');
        if (is_dir($confDir . '/packages/' . $this->environment)) {
            $loader->load($confDir . '/packages/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        }
        $loader->load($confDir . '/services' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/services_' . $this->environment . self::CONFIG_EXTS, 'glob');

        //$loader->load($this->getRootDir() .'/Service/**/*'.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = $this->getProjectDir() . '/config';
        if (is_dir($confDir . '/routes/')) {
            $routes->import($confDir . '/routes/*' . self::CONFIG_EXTS, '/', 'glob');
        }
        if (is_dir($confDir . '/routes/' . $this->environment)) {
            $routes->import($confDir . '/routes/' . $this->environment . '/**/*' . self::CONFIG_EXTS, '/', 'glob');
        }
        $routes->import($confDir . '/routes' . self::CONFIG_EXTS, '/', 'glob');
    }
}
