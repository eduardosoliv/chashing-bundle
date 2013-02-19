How to use
=============================

The easier way to use it, is to create an service:

```yaml
# services.yml
parameters:
    chashing.targets:
        # target name: weight
        server1: 5
        server2: 1
        server3: 2

services:
    chashing:
        class: ESO\CHashingBundle\Main\CHash
        arguments: ["@chashing.hasher", "%chashing.targets%"]

    chashing.hasher:
        class: ESO\CHashingBundle\Hasher\Crc32
```

```php
/* @var $chash \ESO\CHashingBundle\Main\CHash */
$chash = $this->getContainer()->get('chashing');

// simple targets
echo "\nTargets:\n";
print_r($chash->targets()->getTargets());

// simple lookup
echo "\nSimple lookup:\n";
print_r($chash->lookup('test1')); // server1

// ask more than one target, useful for redundant writes
echo "\nLookup more than one server:\n";
print_r($chash->lookup('test1', 2)); // server1 and server3

// another key
echo "\nLookup another key:\n";
print_r($chash->lookup('hash-other-key-2')); // server2

// remove the server1
echo "\nRemove server:\n";
$chash->targets()->del('server1');

// simple lookup after remove server to show the "switch" of server
echo "\nSimple lookup (after remove server):\n";
print_r($chash->lookup('test1')); // server3

// add back server1
$chash->targets()->addMulti(
    array(
        'server1' => 5
    )
);

// simple lookup after remove server to show the "switch" of server
echo "\nSimple lookup (after add back the server):\n";
print_r($chash->lookup('test1')); // server1
```

Results:
```
Targets:
Array
(
    [0] => server1
    [1] => server2
    [2] => server3
)

Simple lookup:
Array
(
    [0] => server1
)

Lookup more than one server:
Array
(
    [0] => server1
    [1] => server3
)

Lookup another key:
Array
(
    [0] => server2
)

Remove server:

Simple lookup (after remove server):
Array
(
    [0] => server3
)

Simple lookup (after add back the server):
Array
(
    [0] => server1
)
```

Some notes:
* The weight is useful to handle servers with different capacity, eg: a server with 8GB of RAM can have a weight of 8, other with 2GB of RAM weight of 2;
* Anothers hashing algorithms can be used than crc32, there is already an example of MD5.
