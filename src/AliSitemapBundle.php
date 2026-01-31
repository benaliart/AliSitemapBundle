<?php

namespace Aliarteo\AliSitemapBundle;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class AliSitemapBundle extends AbstractBundle
{

    const CONFIG_KEY = "ali_sitemap";
    // const BUNDLE_ROUTES_YAML = $this->getBundlePath() . "/config/routes/" . self::CONFIG_KEY . ".yaml";
    const ROUTES_YAML_PATH = "/config/routes/" . self::CONFIG_KEY . ".yaml";
    const CONFIG_YAML_PATH = "/config/packages/" . self::CONFIG_KEY . ".yaml";

    private $current_builder = null;

    public function __construct()
    {
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Save current builder
        $this->current_builder = $builder;

        $filesystem = new Filesystem();

        // BUNDLE DEFAULT CONFIG
        $this->initBundleConfig($filesystem);
        // ADD BUNDLE ROUTES
        $this->initBundleRoutes($filesystem);

    }


public function configure(DefinitionConfigurator $definition): void
{

    /** @var NodeDefinition $rootNode */
    $rootNode = $definition->rootNode();

    $rootNode
        ->children()
            ->booleanNode('robots_txt')
                ->defaultTrue()
            ->end()
            ->scalarNode('default_changefreq')
                ->defaultValue('weekly')
                ->info('Default update frequency for sitemaps.')
            ->end()
            ->floatNode('default_priority')
                ->defaultValue(0.7)
                ->info('Default priority for sitemaps.')
            ->end()
            ->arrayNode('sitemaps')
                ->defaultValue([])
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('slug')
                            ->isRequired()
                            ->info('Unique slug to identify the sitemap. Only alphanumeric characters, hyphens (-), and underscores (_) are allowed. Regex: [a-zA-Z0-9\-_]+')
                        ->end()
                        ->scalarNode('title')
                            ->isRequired()
                            ->info('Title of the sitemap.')
                        ->end()
                        ->scalarNode('changefreq')
                            ->defaultValue('weekly')
                            ->info('Update frequency for this sitemap (optional).')
                        ->end()
                        ->floatNode('priority')
                            ->defaultValue(0.8)
                            ->info('Priority for this sitemap (optional).')
                        ->end()
                        ->arrayNode('nodes')
                            ->defaultValue([])
                            ->arrayPrototype()
                                ->children()
                                    ->enumNode('type')
                                        ->values(['url', 'route', 'routes'])
                                        ->isRequired()
                                        ->info('Type of node: "url", "route", or "routes".')
                                    ->end()
                                    ->scalarNode('title')
                                        ->defaultValue('')
                                        ->info('Title of the node (optional for "routes" type).')
                                    ->end()
                                    ->scalarNode('url')
                                        ->defaultValue('')
                                        ->info('URL for "url" type nodes.')
                                    ->end()
                                    ->scalarNode('route')
                                        ->defaultValue('')
                                        ->info('Route name for "route" or "routes" type nodes.')
                                    ->end()
                                    ->floatNode('priority')
                                        ->defaultValue(0.8)
                                        ->info('Priority for this node (optional).')
                                    ->end()
                                    ->scalarNode('changefreq')
                                        ->defaultValue('weekly')
                                        ->info('Update frequency for this node (optional).')
                                    ->end()
                                    ->scalarNode('entity')
                                        ->defaultValue('')
                                        ->info('Entity class for "routes" type nodes.')
                                    ->end()
                                    ->scalarNode('title_method')
                                        ->defaultValue('')
                                        ->info('Method to get the title for "routes" type nodes.')
                                    ->end()
                                    ->variableNode('route_parameters')
                                        ->defaultValue([])
                                        ->info('Route parameters for "route" or "routes" type nodes.')
                                    ->end()
                                    ->scalarNode('repository_method')
                                        ->defaultValue('findBy')
                                        ->info('Repository method to fetch entities for "routes" type nodes.')
                                    ->end()
                                    ->variableNode('query')
                                        ->defaultValue([])
                                        ->info('Query conditions for "routes" type nodes.')
                                    ->end()
                                    ->variableNode('orderBy')
                                        ->defaultValue([])
                                        ->info('Order by conditions for "routes" type nodes.')
                                    ->end()
                                    ->scalarNode('lastmod_method')
                                        ->defaultValue('')
                                        ->info('Method to get the last modification date for "routes" type nodes.')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {

        // load bundle YAML services file
        $container->import('../config/services.yaml');

        // Create severals parameters
        $container->parameters()
            // Generate Robot Txt or not
            ->set('ali_sitemap.robots_txt', $config['robots_txt'])
            // Default priority
            ->set('ali_sitemap.default_priority', $config['default_priority'])
            // Default changefreq
            ->set('ali_sitemap.default_changefreq', $config['default_changefreq'])
            // List of Sitemaps config
            ->set('ali_sitemap.sitemaps', $config['sitemaps'])
        ;

    }

    private function getAlias(): string
    {
        return self::CONFIG_KEY;
    }

    /**
     * getBundlePath
     * Retrieve the path of this bundle 
     * ex : /path/SfApp/AliSitemapBundle
     */
    public function getBundlePath(): string
    {
        return $this->getPath();
    }

    /**
     * getAppPath
     * Retrieve the path of this App 
     * ex : /path/SfApp
     */
    public function getAppPath(): string
    {
        return $this->current_builder?->getParameter('kernel.project_dir');
    }

    /**
     * initBundleConfig
     * if the config file doesn't exist in the app, copy it from the bundle
     */
    private function initBundleConfig(Filesystem $filesystem): void
    {
        $bundleConfigPath = $this->getBundlePath() . (self::CONFIG_YAML_PATH) . '.sample';
        $appConfigPath = $this->getAppPath() . (self::CONFIG_YAML_PATH);

        if (!$filesystem->exists($appConfigPath)) {
            try {
                $filesystem->copy($bundleConfigPath, $appConfigPath, false);
            } catch (IOExceptionInterface $exception) {
                error_log(sprintf('An error occurred while copying the config file: %s', $exception->getMessage()));
            }
        }
    }

    /**
     * initBundleRoutes
     * if the routes file doesn't exist in the app, copy it from the bundle
     */
    private function initBundleRoutes(Filesystem $filesystem): void
    {
        $bundleRoutesPath = $this->getBundlePath() . (self::ROUTES_YAML_PATH);
        $appRoutesPath = $this->getAppPath() . (self::ROUTES_YAML_PATH);

        if (!$filesystem->exists($appRoutesPath)) {
            try {
                $filesystem->copy($bundleRoutesPath, $appRoutesPath, false);
            } catch (IOExceptionInterface $exception) {
                error_log(sprintf('An error occurred while copying the config file: %s', $exception->getMessage()));
            }
        }

    }

}