Consistent hashing Symfony 2 bundle
=============================

[![Build Status](https://api.travis-ci.org/entering/chashing-bundle.png?branch=master)](https://travis-ci.org/entering/chashing-bundle)

What does this bundle do?
------------

Simple Symfony 2 bundle that implements consistent hashing, one of the most know use case is in distributed caching, but can be used in other cases like shard data across multiple databases/servers.

The source code of this bundle is base Flexihash (https://github.com/pda/flexihash/).

You can read more about consistent hashing in: http://www.tomkleinpeter.com/2008/03/17/programmers-toolbox-part-3-consistent-hashing/

Pull requests are welcome (see Developing section).

Improves/changes
------------

There are some improves/changes to Flexihash:
* Used PHPunit instead of Simpletest;
* Added several validations on adding/removing targets;
* Improved performance of lookup:
  * Used isset instead of in_array (https://github.com/pda/flexihash/blob/master/classes/Flexihash.php#L190);
  * Removed unecessary count's (https://github.com/pda/flexihash/blob/master/classes/Flexihash.php#L196);
  * Simplified the if's inside foreach leading to huge performance improvements  (https://github.com/pda/flexihash/blob/master/classes/Flexihash.php#L181);
* Break the main class in two, one main class and one holding data and methods related to tagerts;
* 100% code coverage;

Requirements
------------

* PHP >= 5.4.7
* PHPUnit 3.7.* (just for development)

Installation
------------

Add the following line to your composer.json file.

```js
//composer.json
{
    //...
    "require": {
        //...
        "eso/chashing-bundle": "dev-master"
    }
    //...
}
```

If you haven't allready done so, get Composer:

```bash
curl -s http://getcomposer.org/installer | php
```

And install the new bundle

```bash
php composer.phar update eso/chashing-bundle
```

Add to your AppKernel.php

```
new ESO\CHashingBundle\ESOCHashingBundle()
```

How to use
------------

The easier way to use it, is to create an service:

```yaml
    eso.chashing:
        class: ESO\CHashingBundle\Main\CHash
        arguments: ["@eso.chashing.hasher"]

    eso.chashing.hasher:
        class: ESO\CHashingBundle\Hasher\Crc32
```

```php
/* @var $chash \ESO\CHashingBundle\Main\CHash */
$chash = $this->container->get('eso.chashing');

// add one target
$chash->targets()->add('server1', 5); // add target test with weight 5

// add multiple targets
$chash->targets()->addMulti(
    array(
        'server2' => 1,
        'server3' => 2
    )
);

// simple lookup
print_r($chash->lookup('test1')); // server1

// ask more than one target, useful for edundant writes
print_r($chash->lookup('test1', 2)); // server1 and server3

// another key
print_r($chash->lookup('hash-other-key-2')); // server2

// remove the server1
$chash->targets()->del('server1');

print_r($chash->lookup('test1')); // server3
```

Some notes:
* The weight is useful to handle servers with different capacity, eg: a server with 8GB of RAM can have a weight of 8, other with 2GB of RAM weight of 2;
* Anothers hashing algorithms can be used than crc32, there is already an example of MD5.

Developing
------------

If you want to contribute:
* fork;
* clone;
* install composer;
* php composer.phar install --dev;
* make your changes and run the tests: phpunit

Coding standards used: PSR-*

And testing should aim 100%

TODO
------------

* Add method to get all targets and weights (can be useful);
* Add a way of configuring the targets of chashing through services of Symfony 2;
* Improve documentation on PHPdocs;
* Add proper documentation on Resources/doc.

