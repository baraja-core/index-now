# IndexNow PHP Protocol

[![Integrity check](https://github.com/baraja-core/index-now/actions/workflows/main.yml/badge.svg)](https://github.com/baraja-core/index-now/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg)](./LICENSE)

Easy-to-use PHP library implementing the IndexNow protocol that allows websites to notify search engines whenever content on any URL is updated or created, enabling instant crawling and discovery.

## 🎯 Key Features

- **Instant indexing** - Notify search engines immediately when your content changes
- **Multi-engine support** - Built-in support for Bing and Yandex, with ability to add custom endpoints
- **Simple API** - Single method call to ping search engines about URL changes
- **Lightweight** - Zero external dependencies, uses native PHP cURL
- **PHP 8.0+** - Modern PHP with strict typing and clean architecture

## 🔍 How It Works

The IndexNow protocol is a simple ping mechanism that informs search engines about recent changes to your website content:

1. **Generate API Key** - Create a unique API key and host it as a text file at your domain root
2. **Initialize Service** - Create an `IndexNow` instance with your API key and target search engine
3. **Send Notifications** - Call `sendChangedUrl()` whenever you update or create content
4. **Search Engine Crawls** - The search engine receives the ping and prioritizes crawling your URL

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Your Website  │     │    IndexNow     │     │  Search Engine  │
│                 │     │     Library     │     │   (Bing/Yandex) │
└────────┬────────┘     └────────┬────────┘     └────────┬────────┘
         │                       │                       │
         │  Content Updated      │                       │
         │──────────────────────>│                       │
         │                       │                       │
         │                       │  HTTP GET with URL    │
         │                       │──────────────────────>│
         │                       │                       │
         │                       │     200 OK            │
         │                       │<──────────────────────│
         │                       │                       │
         │                       │                       │ Crawler visits
         │<─────────────────────────────────────────────│ your URL
         │                       │                       │
```

## 🏗️ Architecture

The library consists of a single `IndexNow` class with a straightforward design:

### Components

| Component | Description |
|-----------|-------------|
| `IndexNow` | Main service class handling API key management and endpoint communication |
| `ENGINE_BING` | Constant for Bing search engine identifier |
| `ENGINE_YAHOO` | Constant for Yandex search engine identifier |
| `ENGINE_ENDPOINT` | Predefined endpoint URL templates for supported engines |

### Supported Search Engines

| Engine | Endpoint |
|--------|----------|
| **Bing** | `https://www.bing.com/indexnow?key={key}&url={url}` |
| **Yandex** | `https://yandex.com/indexnow?key={key}&url={url}` |

## 📦 Installation

It's best to use [Composer](https://getcomposer.org) for installation, and you can also find the package on
[Packagist](https://packagist.org/packages/baraja-core/index-now) and
[GitHub](https://github.com/baraja-core/index-now).

To install, simply use the command:

```shell
$ composer require baraja-core/index-now
```

You can use the package manually by creating an instance of the internal classes.

### Requirements

- PHP 8.0 or higher
- cURL extension enabled

## 🚀 Basic Usage

### Simple Example with Bing

```php
use Baraja\IndexNow\IndexNow;

// Create service instance for Bing
$indexNow = new IndexNow(
    apiKey: 'your-api-key-here',
    searchEngine: IndexNow::ENGINE_BING
);

// Notify search engine about changed URL
$indexNow->sendChangedUrl('https://example.com/updated-page');
```

### Using with Yandex

```php
use Baraja\IndexNow\IndexNow;

// Create service instance for Yandex
$indexNow = new IndexNow(
    apiKey: 'your-api-key-here',
    searchEngine: IndexNow::ENGINE_YAHOO
);

// Send notification
$indexNow->sendChangedUrl('https://example.com/new-article');
```

### Multiple Search Engines

You can notify multiple search engines by adding additional endpoints:

```php
use Baraja\IndexNow\IndexNow;

// Start with Bing
$indexNow = new IndexNow(
    apiKey: 'your-api-key-here',
    searchEngine: IndexNow::ENGINE_BING
);

// Add Yandex endpoint
$indexNow->addEndpointUrl(
    engine: IndexNow::ENGINE_YAHOO,
    endpointUrl: IndexNow::ENGINE_ENDPOINT[IndexNow::ENGINE_YAHOO]
);

// This will now notify both Bing and Yandex
$indexNow->sendChangedUrl('https://example.com/updated-content');
```

### Custom Search Engine Endpoint

You can add custom endpoints for other search engines that support the IndexNow protocol:

```php
use Baraja\IndexNow\IndexNow;

$indexNow = new IndexNow(
    apiKey: 'your-api-key-here',
    searchEngine: IndexNow::ENGINE_BING
);

// Add a custom endpoint (use {key} and {url} placeholders)
$indexNow->addEndpointUrl(
    engine: 'custom-engine',
    endpointUrl: 'https://custom-search.com/indexnow?key={key}&url={url}'
);

$indexNow->sendChangedUrl('https://example.com/page');
```

## 🔑 API Key Setup

Before using IndexNow, you need to set up your API key:

1. **Generate a key** - Create a unique alphanumeric string (e.g., `ecc3bf28ed494de4b01e754cf6dff0d5`)
2. **Create a key file** - Place a text file named `{your-key}.txt` at your website root containing the key itself
3. **Verify accessibility** - Ensure `https://yourdomain.com/{your-key}.txt` returns your key

Example key file location:
```
https://example.com/ecc3bf28ed494de4b01e754cf6dff0d5.txt
```

The content of this file should be:
```
ecc3bf28ed494de4b01e754cf6dff0d5
```

## 📖 API Reference

### Constructor

```php
public function __construct(
    string $apikey,
    string $searchEngine = self::ENGINE_BING
)
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$apikey` | string | - | Your IndexNow API key |
| `$searchEngine` | string | `ENGINE_BING` | Initial search engine to use |

### Methods

#### `sendChangedUrl(string $url): void`

Sends a notification to all configured search engine endpoints about a changed or new URL.

```php
$indexNow->sendChangedUrl('https://example.com/updated-page');
```

#### `addEndpointUrl(string $engine, string $endpointUrl): void`

Adds a new search engine endpoint. The `{key}` placeholder in the URL will be automatically replaced with your API key.

```php
$indexNow->addEndpointUrl('custom', 'https://search.com/indexnow?key={key}&url={url}');
```

### Constants

| Constant | Value | Description |
|----------|-------|-------------|
| `ENGINE_BING` | `'bing'` | Bing search engine identifier |
| `ENGINE_YAHOO` | `'yahoo'` | Yandex search engine identifier |
| `ENGINE_ENDPOINT` | array | Mapping of engine names to endpoint URLs |

## 💡 Best Practices

1. **Call on actual changes** - Only ping when content genuinely changes to maintain trust with search engines
2. **Batch wisely** - For bulk updates, consider spacing out notifications
3. **Verify key setup** - Ensure your API key file is publicly accessible before making calls
4. **Monitor responses** - While the library doesn't return responses, monitor your search console for indexing status
5. **Use in production** - IndexNow is designed for production environments; avoid excessive testing calls

## 🔗 External Resources

- [IndexNow Official Website](https://www.indexnow.org/)
- [Bing IndexNow Documentation](https://www.bing.com/indexnow)
- [Yandex IndexNow Documentation](https://yandex.ru/support/webmaster/indexing-options/index-now.html)

## 👤 Author

**Jan Barasek** - [https://baraja.cz](https://baraja.cz)

## 📄 License

`baraja-core/index-now` is licensed under the MIT license. See the [LICENSE](https://github.com/baraja-core/index-now/blob/master/LICENSE) file for more details.
