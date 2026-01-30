# Configuration Guide

## `ali_sitemap.yaml` Configuration

Create the `ali_sitemap.yaml` file inside `config/packages` folder with `ali_sitemap` configuration key:

## Sitemaps Configuration

`sitemaps` key under ali_sitemap contains an array of sitemap configurations \(e\.g\., blog\), each with its own settings\.

## Configuration Exemple

```yaml
ali_sitemap:
    default_priority: 0.7 # default priority for each url entries
    default_changefreq: "weekly" # default change frequency for each url entries

    sitemaps:

        #### First Sitemap example :
        # it will create a sitemap-pages.xml Sitemap :
        - slug: "pages" 
          # For sitemap inside twig template with controller call :
          title: "Pages"
          # edit the default priority :
          priority: 0.9 
          # edit the default changefreq :
          changefreq: "weekly" 
          # >>> List of url entries (nodes) :
          nodes:
              # type of node (url / route / routes) : 
              - type: "route" 
                title: "Accueil" 
                # the route call :
                route: "app_home"
                priority: 1.0
                 # edit the default changefreq :
                changefreq: "daily"

              - type: "route"
                title: "À propos"
                route: "app_about"
                # edit the default priority :
                priority: 0.9 

        #### 2nd Sitemap example :
        # it will create a sitemap-blog.xml Sitemap :
        - slug: "blog"
          # For sitemap inside twig template with controller call : 
          title: "Blog" 
          # >>> List of url entries (nodes) :
          nodes:
              # Type Url : force a relative url :
              - type: "url" 
                title: "Hello word"
                # Relative URL :
                url: "/hello/world" 
                priority: 0.7

              # Type Route : url general from route name :
              - type: "route"
                # For sitemap inside twig template with controller call : 
                title: "Blog homepage" 
                # The route name
                route: "app_article_index" 
                # edit the default priority :
                priority: 0.8 

              # Type Routes : dynamic routes from basic repository results :
              - type: "routes"
                # Entity used for the repository query :
                entity: 'App\Entity\Article' 
                # Title method : For sitemap inside twig template with controller call :
                title_method: "getTitle" 
                # Route name :
                route: "app_article_show" 
                # Route parameter { key : "method" } :
                route_parameters: { id: "getId" } 
                # default : findBy (or "mySpecialRequestForSitemapBlog ) :
                repository_method: "findBy" 
                # Query array ( ex : ["isActive" => true ]) :
                query: { isActive: true } 
                # OrderBy array ( ex : ["publishedAt" => "DESC" ] :
                orderBy: { publishedAt: "DESC" } 
                #-- Final Repo Query will be --#
                #-- findBy(["isActive" => true ], ["publishedAt" => "DESC" ])) --#
                # Method for last modification date :
                lastmod_method: "getModifiedAt" 
                # Force changefreq :
                changefreq: "weekly" 
                # Force priority :
                priority: 0.9 
```




