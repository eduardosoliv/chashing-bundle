<?php

/*
 * This file is a part of the PHP Consistent Hashing Bundle.
 *
 * (c) 2013 Eduardo Oliveira
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ESO\CHashingBundle\Tests\Main;

use ESO\CHashingBundle\Main\CHash;
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
     * @var \ESO\CHashingBundle\Main\CHash
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

    /**************************************************************************
     * Test constructor.
     *************************************************************************/

    /**
     * Test constructor with replicas as array (instead of a positive integer).
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Number of replicas needs to be a positive integer
     */
    public function testConstructorReplicasAsArray()
    {
        new CHash(
            new Hasher\Crc32(),
            array('test')
        );
    }

    /**
     * Test constructor with replicas as negative integer.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Number of replicas needs to be a positive integer
     */
    public function testConstructorReplicasNegativeInteger()
    {
        new CHash(
            new Hasher\Crc32(),
            -10
        );
    }

    /**
     * Test constructor with replicas as float.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Number of replicas needs to be a positive integer
     */
    public function testConstructorReplicasFloat()
    {
        new CHash(
            new Hasher\Crc32(),
            10.5
        );
    }
}
