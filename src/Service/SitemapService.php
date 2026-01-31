<?php

namespace Aliarteo\AliSitemapBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SitemapService
{

    public function __construct(
        // private DocumentationService $documentationService,
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $router,
        private string $robots_txt,
        private string $default_changefreq,
        private string $default_priority,
        private array $sitemaps_config

    ) {
    }

    public function getRobotTxt(): string
    {

        $robots_txt = $this->robots_txt;
        $sitemap_index_url = $this->router->generate("ali_sitemaps_index", [], UrlGeneratorInterface::ABSOLUTE_URL);
        return $sitemap_index_url;

    }

    public function getSitemaps(): array
    {

        $sitemaps_config = $this->sitemaps_config;

        $sitemaps = [];
        foreach ($sitemaps_config as $sitemap_config) {
            $sitemap['loc'] = "/sitemap-" . $sitemap_config['slug'] . ".xml";
            $sitemaps[] = $sitemap;
        }
        return $sitemaps;

    }

    public function getSitemap($slug): ?array
    {

        $sitemaps_config = $this->sitemaps_config;

        $sitemaps = [];
        foreach ($sitemaps_config as $sitemap_config) {
            if ($sitemap_config['slug'] == $slug)
                return $sitemap_config;
        }
        return null;

    }

    public function getSitemapNodes($slug): ?array
    {
        if (!$sitemap_config = $this->getSitemap($slug))
            return null;

        $sitemaps_config = $this->sitemaps_config;
        $default_changefreq = $this->default_changefreq;
        $default_priority = $this->default_priority;

        $nodes = [];

        // Le paramètre nodes de la conf YML rassemble les urls du site map
        // Conf des routes principales
        if (isset($sitemap_config['nodes'])) {

            foreach ($sitemap_config['nodes'] as $node) {


                /**
                 * NODE TYPE URL
                 * Une url fixe
                 */
                if ($node['type'] == "url") {
                    if ($node['url'])
                        $p['loc'] = $node['url'];
                }

                /**
                 * NODE TYPE ROUTE (SINGLE)
                 * Une issue d'une route
                 */ elseif ($node['type'] == "route") {
                    if (null != $this->router->getRouteCollection()->get($node['route'])) {
                        if (isset($node['route_parameters'])) {
                            $route_parameters = $node['route_parameters'];
                        }
                        $url = $this->router->generate($node['route'], $route_parameters);
                        $p['loc'] = $url;
                    }
                }

                /**
                 * NODE TYPE ROUTES
                 *
                 * Le paramètre routes de la conf YML rassemble les autres routes à générer ces routes ont des
                 * parametres et permettent de récupérer la liste des Articles par exemple
                 * routes => ['priority:String','entity:Class','query:Array', 'orderBy:Array']
                 * Pour chaque routes ( routes est un tableau défini dans la configuration pour chaque sitemap)
                 * On ajoute une liste d'url au sitemap
                 */ elseif ($node['type'] == "routes") {

                    $entity = $query = $orderBy = null;

                    if (null != $this->router->getRouteCollection()->get($node['route'])) {


                        if (isset($node['entity'])) {
                            $entity = $node['entity'];
                            if (isset($node['query']))
                                $query = $node['query'];
                            else
                                $query = [];

                            if (isset($node['orderBy']))
                                $orderBy = $node['orderBy'];
                            else
                                $orderBy = [];

                            if (isset($node['repository_method']))
                                $repositoryMethod = $node['repository_method'];
                            else
                                $repositoryMethod = "findBy";

                            $items = $this->em->getRepository($entity)
                                ->$repositoryMethod($query, $orderBy);

                            foreach ($items as $item) {
                                $p = array();
                                $route_parameters = $generate_route_parameters = array();

                                if (isset($node['route_parameters'])) {
                                    $route_parameters = $node['route_parameters'];
                                    foreach ($route_parameters as $attribut => $getMethod) {
                                        $generate_route_parameters[$attribut] = $item->$getMethod();
                                    }
                                }

                                $url = $this->router->generate($node['route'], $generate_route_parameters);
                                $p['loc'] = $url;

                                // Si "title_method" est défini par une methode qui marche
                                if (isset($node['title_method'])) {
                                    $titleMethod = $node['title_method'];
                                    $p['title'] = $item->$titleMethod();
                                } else {
                                    $p['title'] = "La méthode pour récupérer le titre n'est pas configurée, ajouter title_method : \"methodeGetTitle\" au fichier de configuration yaml";
                                }

                                // Si "lastmod_method" est défini par une methode qui marche
                                if (isset($node['lastmod_method'])) {
                                    $lastmodMethod = $node['lastmod_method'];
                                    $p['lastmod'] = $item->$lastmodMethod();
                                }

                                // Si "priority" est défini
                                if (isset($node['priority'])) {
                                    $p['priority'] = $node['priority'];
                                }
                                // Sinon si "priority" du sitemap est défini
                                elseif (isset($sitemap_config['priority'])) {
                                    $p['priority'] = $sitemap_config['priority'];
                                } else {
                                    $p['priority'] = $default_priority;
                                }

                                // Si "changefreq" est défini
                                if (isset($node['changefreq'])) {
                                    $p['changefreq'] = $node['changefreq'];
                                }
                                // Sinon si "changefreq" du sitemap est défini
                                elseif (isset($sitemap_config['changefreq'])) {
                                    $p['changefreq'] = $sitemap_config['changefreq'];
                                } else {
                                    $p['changefreq'] = $default_changefreq;
                                }

                                // if(isset($value['changefreq']))
                                //     $p['changefreq'] = $value['changefreq'];
                                // if(isset($value['priority']))
                                //     $p['priority'] = $value['priority'];

                                if (isset($p['loc']))
                                    $nodes[] = $p;
                            }

                        }

                        // dd('stop');
                        // $url = $this->router->generate($value['route']);
                    }

                }


                // PRIORITY
                // Si "priority" est défini dans le node
                if (isset($node['priority'])) {
                    $p['priority'] = $node['priority'];
                }
                // Sinon si "priority" du sitemap est défini
                elseif (isset($node['priority'])) {
                    $p['priority'] = $sitemap_config['priority'];
                } else {
                    $p['priority'] = $default_priority;
                }

                // CHANGEFREQ
                // Si "changefreq" est défini
                if (isset($node['changefreq'])) {
                    $p['changefreq'] = $node['changefreq'];
                }
                // Sinon si "changefreq" du sitemap est défini
                elseif (isset($sitemap_config['changefreq'])) {
                    $p['changefreq'] = $sitemap_config['changefreq'];
                } else {
                    $p['changefreq'] = $default_changefreq;
                }

                // TITRE de la node
                if (isset($node['title'])) {
                    $p['title'] = $node['title'];
                }

                // Ajouter l'entrée aux nodes du sitemap
                if (isset($p['loc']) && $node['type'] != 'routes')
                    $nodes[] = $p;

            }
        }

        return $nodes;

    }


}
