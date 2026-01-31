<?php

namespace Aliarteo\AliSitemapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Aliarteo\AliSitemapBundle\Service\SitemapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{

    public function __construct(
        private SitemapService $sitemapService,
    ) {
    }


    /**
     * robots.txt
     * Robots text file with link to sitemap index
     *
     * @param  mixed $doc_key
     * @return Response
     */
    #[Route(
        '/robots.txt',
        name: 'ali_sitemaps_robots_txt'
    )]
    public function robotTxt(Request $request): Response
    {

        $hostname = $request->getSchemeAndHttpHost();
        $template = "@AliSitemap/robots_txt.html.twig";

        $sitemap_index_url = $this->sitemapService->getRobotTxt();

        // return response in Txt format
        $response = new Response(
            $this->renderView($template, [
                'sitemap_index_url' => $sitemap_index_url,
            ]),
            200
        );
        $response->headers->set('Content-Type', 'text/plain');
        return $response;

    }

    /**
     * index of the sitemaps
     * A list of sitemaps define in ali_sitemap.yaml config
     *
     * @param  mixed $doc_key
     * @return Response
     */
    #[Route(
        '/sitemap_index.xml',
        name: 'ali_sitemaps_index'
    )]
    public function index(Request $request): Response
    {

        $hostname = $request->getSchemeAndHttpHost();
        $sitemaps = $this->sitemapService->getSitemaps();
        $template = "@AliSitemap/index.html.twig";

        // return response in XML format
        $response = new Response(
            $this->renderView($template, [
                'hostname' => $hostname,
                'sitemaps' => $sitemaps,
            ]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');
        return $response;

    }

    /**
     * sitemapXML
     *
     * @param  mixed $doc_key
     * @return Response
     */

    #[Route(
        '/sitemap-{slug}.xml',
        name: 'ali_sitemap_show',
        requirements: ['slug' => '[a-zA-Z0-9\-_]+']
    )]
    function sitemapXML(Request $request, $slug)
    {
        if (!$nodes = $this->sitemapService->getSitemapNodes($slug))
            throw $this->createNotFoundException("no sitemap found");

        $hostname = $request->getSchemeAndHttpHost();

        $template = "@AliSitemap/sitemap.html.twig";

        // return response in XML format
        $response = new Response(
            $this->renderView('@AliSitemap/sitemap.html.twig', array(
                'nodes' => $nodes,
                'hostname' => $hostname
            )),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;

    }

}
