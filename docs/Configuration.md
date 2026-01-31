# Configuration Guide

## `ali_sitemap.yaml` Configuration

Create the `ali_sitemap.yaml` file inside the `config/packages` directory and define your configuration under the `ali_sitemap` root key.

The sitemap Index (site_index.xml) will list all sitemaps

## Sitemaps Configuration

The `sitemaps` key under `ali_sitemap` contains a list of sitemap definitions (e.g. `pages`, `blog`), each with its own configuration.

Each sitemap generates a dedicated XML file, such as:
- `sitemap-pages.xml`
- `sitemap-blog.xml`

## Configuration Example

```yaml
ali_sitemap:
    # Default priority applied to all URL entries
    default_priority: 0.7

    # Default change frequency applied to all URL entries
    default_changefreq: "weekly"

    sitemaps:

        #### First sitemap example
        # This will generate a sitemap-pages.xml file
        - slug: "pages"

          # Used when rendering the sitemap via Twig / controller
          title: "Pages"

          # Override the default priority
          priority: 0.9

          # Override the default change frequency
          changefreq: "weekly"

          # List of URL entries (nodes)
          nodes:

              # Route-based node
              - type: "route"
                title: "Home"

                # Symfony route name
                route: "app_home"

                # Override the priority
                priority: 1.0

                # Override the change frequency
                changefreq: "daily"

              - type: "route"
                title: "About"
                route: "app_about"

                # Override the priority
                priority: 0.9

        #### Second sitemap example
        # This will generate a sitemap-blog.xml file
        - slug: "blog"

          # Used when rendering the sitemap via Twig / controller
          title: "Blog"

          # List of URL entries (nodes)
          nodes:

              # URL node (relative URL)
              - type: "url"
                title: "Hello world"

                # Relative URL
                url: "/hello/world"

                priority: 0.7

              # Route-based node
              - type: "route"
                title: "Blog homepage"

                # Symfony route name
                route: "app_article_index"

                priority: 0.8

              # Dynamic routes generated from repository results
              - type: "routes"

                # Entity used for the repository query
                entity: "App\Entity\Article"

                # Method used to retrieve the title
                title_method: "getTitle"

                # Symfony route name
                route: "app_article_show"

                # Route parameters mapping { parameter: "entityMethod" }
                route_parameters: { id: "getId" }

                # Repository method (default: findBy)
                repository_method: "findBy"

                # Query criteria (e.g. ["isActive" => true])
                query: { isActive: true }

                # OrderBy clause (e.g. ["publishedAt" => "DESC"])
                orderBy: { publishedAt: "DESC" }

                # Final repository call:
                # findBy(["isActive" => true], ["publishedAt" => "DESC"])

                # Method used to retrieve the last modification date
                lastmod_method: "getModifiedAt"

                # Override the change frequency
                changefreq: "weekly"

                # Override the priority
                priority: 0.9
```

# Generated Sitemap Output

## sitemap_index.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>https://127.0.0.1:8003/sitemap-pages.xml</loc>
    </sitemap>
    <sitemap>
        <loc>https://127.0.0.1:8003/sitemap-blog.xml</loc>
    </sitemap>
</sitemapindex>
```

## sitemap-pages.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <url>
        <loc>https://127.0.0.1:8003/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>https://127.0.0.1:8003/about</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

</urlset>
```

## sitemap-blog.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <url>
        <loc>https://127.0.0.1:8003/coucou/test</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>https://127.0.0.1:8003/article</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>https://127.0.0.1:8003/article/1</loc>
        <lastmod>2026-01-29</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>https://127.0.0.1:8003/article/2</loc>
        <lastmod>2026-01-29</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

</urlset>
```


