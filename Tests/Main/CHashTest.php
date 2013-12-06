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
 * @author Eduardo Oliveira <entering@gmail.com>
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
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
     * @expectedExceptionMessage replicas needs to be a positive integer
     */
    public function testConstructorReplicasAsArray()
    {
        new CHash(
            new Hasher\Crc32(),
            array(),
            array('test')
        );
    }

    /**
     * Test constructor with replicas as negative integer.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage replicas needs to be a positive integer
     */
    public function testConstructorReplicasNegativeInteger()
    {
        new CHash(
            new Hasher\Crc32(),
            array(),
            -10
        );
    }

    /**
     * Test constructor with replicas as float.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage replicas needs to be a positive integer
     */
    public function testConstructorReplicasFloat()
    {
        new CHash(
            new Hasher\Crc32(),
            array(),
            10.5
        );
    }

    /**
     * Test constructor with targets.
     */
    public function testConstructorWithTargets()
    {
        $targets = array(
            'test1' => 3,
            'test2' => 2,
            'test3' => 1
        );

        $chash = new CHash(
            new Hasher\Crc32(),
            $targets
        );

        $this->assertEquals(array_keys($targets), $chash->targets()->getTargets());
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
     * Test lookup with targets count as negative integer.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Targets count needs to be a positive integer
     */
    public function testLookupTargetsCountAsNegativeInteger()
    {
        $this->chash->lookup('test', -10);
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

        $this->assertInternalType('array', $resTargets);

        $targetsNames = array_keys($targets);
        sort($targetsNames);
        sort($resTargets);

        $this->assertCount(count($targetsNames), $resTargets);
        $this->assertEquals($targetsNames, $resTargets);
    }

    /**
     * Test lookup.
     */
    public function testLookup()
    {
        $targets = array(
            'test1' => 1,
            'test2' => 1,
            'test3' => 1
        );
        $targetsCount = count($targets);
        $this->chash->targets()->addMulti($targets);

        for ($count = 1; $count <= $targetsCount; ++$count) {
            $resTargets = $this->chash->lookup('test', $count);
            $this->assertInternalType('array', $resTargets);
            $this->assertCount($count, $resTargets);

            // ensure uniqueness
            $this->assertEquals($resTargets, array_unique($resTargets));

            // assert all targets returned "exists"
            foreach ($resTargets as $target) {
                $this->assertTrue(isset($targets[$target]));
            }
        }
    }

    /**
     * Test with a target with much bigger weight.
     *
     * @group fuzzing
     */
    public function testProbability()
    {
        $targets = array(
            'test1' => 2,
            'test2' => 30, // weight much bigger
            'test3' => 2,
            'test4' => 2,
            'test5' => 2,
            'test6' => 2
        );

        $this->chash->targets()->addMulti($targets);

        // initialize array to hold counts
        $targetsCount = $targets;
        foreach (array_keys($targetsCount) as $targetName) {
            $targetsCount[$targetName] = 0;
        }

        for ($i = 1; $i <= 5000; ++$i) {
            $resTargets = $this->chash->lookup(
                $this->generateRandomString(),
                1
            );
            foreach ($resTargets as $target) {
                ++$targetsCount[$target];
            }
        }

        $this->assertTrue(
            $targetsCount['test2'] > ($targetsCount['test1'] +
            $targetsCount['test3'] + $targetsCount['test4'] +
            $targetsCount['test5'] + $targetsCount['test6'])
        );
    }

    protected function generateRandomString(
        $length = 20,
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) {
        return substr(str_shuffle($chars), 0, $length);
    }
}
