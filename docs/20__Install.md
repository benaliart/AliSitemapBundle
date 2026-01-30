# Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation\.

## 1 : Download the Bundle

Open a command console, enter your project directory and execute:

```console
git clone https://github.com/benaliart/AliSitemapBundle.git AliSitemapBundle
```

this will create a directory `AliSitemapBundle` at your project root with the bundle in it.

## 2 : Edit composer.json

Open the `composer.json` file at the root of your project
Edit or Add the `repositories` entry, to precise the path of the bundle downloaded (/AliSitemapBundle) 
Add this lines :
```json
    "repositories": [
        {
            "type": "path",
            "url": "AliSitemapBundle"
        }
    ],
```

Edit the `autoload` and `autoload.dev` entries, and add `"Aliarteo\\AliSitemapBundle\\": "AliSitemapBundle/src"` to them,
Your composer.json should look like this :
```json
    "autoload": { 
        "psr-4": { 
            "App\\": "src/", 
            "Aliarteo\\AliSitemapBundle\\": "AliSitemapBundle/src"
        } 
    }, 
    "autoload-dev": { 
        "psr-4": { 
            "App\\Tests\\": "tests/", 
            "Aliarteo\\AliSitemapBundle\\Tests\\": "AliSitemapBundle/tests/" 
        } 
    },
```

## 3 : Install the bundle

```console
composer require aliarteo/ali-docs-bundle
```

## 4 : Configure your sitemaps
[about bundle configuration](30__Configuration.md)