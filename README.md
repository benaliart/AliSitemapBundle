# AliSitemapBundle
*A Symfony Bundle for Simple Sitemap Generation*

### 📌 Introduction
**AliSitemapBundle** is a lightweight Symfony bundle designed to simplify sitemap generation. It allows you to quickly define and publish sitemaps using a YAML configuration file.

### ✨ Features
   Feature | Description |
 |---------|-------------|
 | **YAML Configuration** | Define sitemaps and nodes directly in the bundle's config file. |
 | **Dynamic URLs** | Generate URLs dynamically using `Entity`, `Repository`, `Order`, ... |
| **robots.txt** | Create robots.txt with link to the sitemap |
 | **Customizable Templates** | Easily override Twig templates for custom output. |
 | **No Database Needed** | Works without a database—just configure and go. |

### 📦 Installation
Open a command console, enter your project directory and execute:
```console
composer require aliarteo/ali-sitemap-bundle
```

### ⚙️ Configuration
Configure the bundle by editing the YAML file. See the [Configuration Guide](docs/Configuration.md).

### 🚀 Roadmap
- Twig integration: Embed sitemap URLs in Twig templates.
- Extend with custom node types.
- Cache sitemaps for performance optimization.

### 📄 License
MIT License
© 2025 AliSitemapBundle
[View LICENSE](LICENSE)


