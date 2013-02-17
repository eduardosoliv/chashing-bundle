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

    /**************************************************************************
     * Test lookup.
     *************************************************************************/

    /**
     * Test lookup with key as array.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid key.
     */
    public function testLookupKeyAsArray()
    {
        $this->chash->lookup(array('test'), 2);
    }

    /**
     * Test lookup with empty key.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid key.
     */
    public function testLookupKeyEmptyString()
    {
        $this->chash->lookup('', 2);
    }

    /**
     * Test lookup with targets count as array.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Targets count needs to be a positive integer
     */
    public function testLookupTargetsCountAsArray()
    {
        $this->chash->lookup('test', array(2));
    }

    /**
     * Test lookup more targets than exists.
     */
    public function testLookupMoreTargetsThanExists()
    {
        $targets = array(
            'test1' => 1,
            'test2' => 1,
            'test3' => 1,
        );

        $this->chash->targets()->addMulti($targets);

        $resTargets = $this->chash->lookup('test', 5);

        $targetsNames = array_keys($targets);
        sort($targetsNames);
        sort($resTargets);

        $this->assertCount(count($targetsNames), $resTargets);
        $this->assertEquals($targetsNames, $resTargets);
    }

//    public function testLookup()
//    {
//        $this->chash->targets()->addMulti(
//            array(
//                'test1' => 1,
//                'test2' => 1,
//                'test3' => 10,
//                'test4  ' => 1,
//                'test5' => 1,
//                'test6' => 1,
//                'test7' => 1,
//            )
//        );
//
//        $targetsDist = array();
//
//        for ($i = 1; $i <= 1000; ++$i) {
//            $targets = $this->chash->lookup($this->generateRandomString(), 1);
//
//            foreach ($targets as $target) {
//                (!isset($targetsDist[$target])) ?
//                    $targetsDist[$target] = 1 :
//                    ++$targetsDist[$target];
//            }
//        }
//
//        print_r($targetsDist);
//    }

    protected function generateRandomString($length = 20, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        return substr(str_shuffle($chars), 0, $length);
    }
}
