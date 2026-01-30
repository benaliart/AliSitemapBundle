<?php

namespace Aliarteo\AliSitemapBundle\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AliSitemapRoutingLoader extends Loader
{
    private bool $isLoaded = false;

    public function __construct(
        private array $sitemaps,
    ) {
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if ($this->isLoaded) {
            throw new \RuntimeException('AliSitemap routes are already loaded!');
        }

        $routes = new RouteCollection();

        // index des sitemaps
        $route = new Route(
            "/sitemap.xml",
            [
                '_controller' => 'Aliarteo\\AliSitemapBundle\\Controller\\SitemapController::index',
            ]
        );
        $routeName = 'ali_sitemaps_index';
        $routes->add($routeName, $route);


        $this->isLoaded = true;
        return $routes;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return 'ali_sitemap' === $type;
    }
}
