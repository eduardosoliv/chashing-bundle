<?php

/*
 * This file is a part of the PHP Consistent Hashing Bundle.
 *
 * (c) 2013 Eduardo Oliveira
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ESO\CHashingBundle\Tests;

use ESO\CHashingBundle\CHash;
use ESO\CHashingBundle\Hasher;

/**
 * CHash tests.
 *
 * @author  Eduardo Oliveira <entering@gmail.com>
 */
class CHashTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Consistent Hash.
     *
     * @var \ESO\CHashingBundle\CHash
     */
    private $chash;

    /**
     * Set up.
     *
     * Before a test is run,  setUp() is invoked.
     *
     * {@see http://www.phpunit.de/manual/current/en/fixtures.html}
     */
    protected function setUp()
    {
        $this->chash = new CHash(
            new Hasher\Crc32()
        );
    }

    public function testSingle()
    {

    }
}
