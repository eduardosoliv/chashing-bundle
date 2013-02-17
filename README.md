Consistent hashing Symfony 2 bundle
=============================

What does this bundle do?
------------

Simple Symfony 2 bundle that implements consistent hashing, one of the most know use case is in distributed caching, but can be used in other cases like shard data across multiple serve.

The base of this bundle is Flexihash code (https://github.com/pda/flexihash/)

You can read more about consistent hashing in: http://www.tomkleinpeter.com/2008/03/17/programmers-toolbox-part-3-consistent-hashing/

Requirements
------------

* PHP 5.3.*
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
