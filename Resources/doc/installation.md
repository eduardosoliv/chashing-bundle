Installation
=============================

Requirements
------------

* PHP >= 5.4

Installation
------------

### Composer

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

```php
// app/AppKernel.php
<?php
    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new ESO\CHashingBundle\ESOCHashingBundle(),
        );
    }
```
