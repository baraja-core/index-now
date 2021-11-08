Index now PHP protocol
======================

Easy to use protocol that websites can call to notify search engines whenever website contents on any URL is updated or created allowing instant crawling, and discovery of the URL.

More information:

- [Bing official site](https://bing.com/indexnow)
- [Yandex official documentation](https://yandex.ru/support/webmaster/indexing-options/index-now.html)

📦 Installation
---------------

It's best to use [Composer](https://getcomposer.org) for installation, and you can also find the package on
[Packagist](https://packagist.org/packages/baraja-core/index-now) and
[GitHub](https://github.com/baraja-core/index-now).

To install, simply use the command:

```
$ composer require baraja-core/index-now
```

You can use the package manually by creating an instance.

How to use
----------

Simply create instance and send request to search engine:

```php
// create service instance for Bing
$indexNow = new \Baraja\IndexNow\IndexNow(
	apiKey: 'ecc3bf28ed494de4b01e754cf6dff0d5',
	searchEngine: 'bing'
);

// send changed URL
$indexNow->sendChangedUrl('https://baraja.cz');
```

And that's all.

📄 License
-----------

`baraja-core/index-now` is licensed under the MIT license. See the [LICENSE](https://github.com/baraja-core/index-now/blob/master/LICENSE) file for more details.
