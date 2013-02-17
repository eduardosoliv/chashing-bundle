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

use ESO\CHashingBundle\Main\Targets;
use ESO\CHashingBundle\Hasher;

/**
 * Targets tests.
 *
 * @author  Eduardo Oliveira <entering@gmail.com>
 */
class TargetsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Consistent Hash.
     *
     * @var \ESO\CHashingBundle\Main\Targets
     */
    private $targets;

    /**
     * Set up.
     *
     * Before a test is run,  setUp() is invoked.
     *
     * {@see http://www.phpunit.de/manual/current/en/fixtures.html}
     */
    protected function setUp()
    {
        $this->targets = new Targets(new Hasher\Crc32());
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
        new Targets(
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
        new Targets(
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
        new Targets(
            new Hasher\Crc32(),
            10.5
        );
    }

    /**************************************************************************
     * Test add.
     *************************************************************************/

    /**************************************************************************
     * Errors/validations.
     *************************************************************************/

    /**
     * Test add with name as array.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid target name
     */
    public function testAddNameAsArray()
    {
        $this->targets->add(array('ola'));
    }

    /**
     * Test add with empty string.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid target name
     */
    public function testAddNameEmptyString()
    {
        $this->targets->add('');
    }

    /**
     * Test add with weight as array.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Weight needs to be a positive integer
     */
    public function testAddNameWeightAsArray()
    {
        $this->targets->add('test', array(3));
    }

    /**
     * Test add with negative integer weigh.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Weight needs to be a positive integer
     */
    public function testAddNameWeightNegativeInteger()
    {
        $this->targets->add('test', -10);
    }

    /**
     * Test add with float weigh.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Weight needs to be a positive integer
     */
    public function testAddNameWeightFloat()
    {
        $this->targets->add('test', 10.5);
    }

    /**
     * Test add duplicate target.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage already present on mapping
     */
    public function testAddDuplicateName()
    {
        $this->targets->add('test');
        $this->targets->add('test');
    }

    /**************************************************************************
     * Internal data structures.
     *************************************************************************/

    /**
     * Test targets positions internal data structure.
     */
    public function testAddTargetsPositions()
    {
        // add targets
        $targets = array(
            'test1' => 3,
            'test2' => 2
        );
        foreach ($targets  as $name => $weight) {
             $this->targets->add($name, $weight);
        }

        // get targetsPositions
        $reflect = new \ReflectionClass($this->targets);
        $targetsPositionsProp = $reflect->getProperty('targetsPositions');
        $targetsPositionsProp->setAccessible(true);
        $targetsPositions = $targetsPositionsProp->getValue($this->targets);

        // assert that the two targets are there
        $this->assertCount(count($targets), $targetsPositions);

        foreach ($targetsPositions as $name => $positions) {
            $this->assertTrue(isset($targets[$name]));
            $this->assertCount(
                $targets[$name] * $this->targets->getNumberReplicas(),
                $positions
            );
        }
    }

    /**
     * Test positions targets internal data structure.
     */
    public function testAddPositionsTargets()
    {
        // add targets
        $targets = array(
            'test1' => 3,
            'test2' => 2
        );
        $totalPositions = 0;
        foreach ($targets  as $name => $weight) {
             $this->targets->add($name, $weight);
             $totalPositions += $this->targets->getNumberReplicas() * $weight;
        }

        // get targetsPositions
        $reflect = new \ReflectionClass($this->targets);
        $positionsTargetsProp = $reflect->getProperty('positionsTargets');
        $positionsTargetsProp->setAccessible(true);
        $positionsTargets = $positionsTargetsProp->getValue($this->targets);

        // assert that all the positions are there
        $this->assertCount($totalPositions, $positionsTargets);

        // assert total of positions of each target
        $targetsPositionsTotal = array_count_values($positionsTargets);
        foreach ($targetsPositionsTotal as $name => $numberPositions) {
            $this->assertTrue(isset($targets[$name]));
            $this->assertEquals($targets[$name] * $this->targets->getNumberReplicas(), $numberPositions);
        }
    }

    /**
     * Test count and sorted.
     */
    public function testAddCountSorted()
    {
        $this->targets->add('test');
        $this->targets->add('test2');

        // test count
        $reflect = new \ReflectionClass($this->targets);
        $targetCountProp = $reflect->getProperty('targetCount');
        $targetCountProp->setAccessible(true);
        $this->assertEquals(2, $targetCountProp->getValue($this->targets));

        // test sorted
        $positionsTargetsSortedProp = $reflect->getProperty('positionsTargetsSorted');
        $positionsTargetsSortedProp->setAccessible(true);
        $this->assertFalse($positionsTargetsSortedProp->getValue($this->targets));
    }

    /**************************************************************************
     * Test add multi.
     *************************************************************************/

    /**
     * Test add multi
     */
    public function testAddMulti()
    {
        $targets = array(
            'test1' => 1,
            'test2' => 3,
            'test3' => 2,
        );

        $this->targets->addMulti($targets);

        // test count
        $reflect = new \ReflectionClass($this->targets);
        $targetCountProp = $reflect->getProperty('targetCount');
        $targetCountProp->setAccessible(true);
        $this->assertEquals(count($targets), $targetCountProp->getValue($this->targets));
    }

}
