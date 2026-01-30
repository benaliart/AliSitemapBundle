# AliSiteampBundle
A Symfony Bundle for easy sitemaps

## Introduction

AliSitemap is a simple tool to facilitate the sitempas creation inside symfony projects
The purpose is to quickly publish sitemaps with simple configurations

You just have the define your list sitemaps and list of nodes inside the yaml bundle

## Features

- Create as sitemaps as needed by edition the bundle config file

- Create url nodes, with url, with route and dynamic routes (by configuring `Entity` and `Repository`, `Order`, ...)

- Easy overwrite templates

- No database requested

## Documentation

- [installation](docs/20__Install.md)

- [Configuration](docs/30__Configuration.md)

## Todo and ideas

- Create a list of Urls that can be embeded in a twig

- Manage a robots.txt file

- Facilitate the possibility of creating new type of nodes (for custom purpose)

- cache sitemap to avoid heavy database calls for large website


# Licence
MIT License

Copyright (c) 2025 AliSitemapBundle

[see LICENCE file](LICENCE)
