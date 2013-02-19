How to use
=============================

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
