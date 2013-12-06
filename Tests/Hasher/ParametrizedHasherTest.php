<?php

/*
 * This file is a part of the PHP Consistent Hashing Bundle.
 *
 * (c) 2013 Eduardo Oliveira
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ESO\CHashingBundle\Tests\Hasher;

use ESO\CHashingBundle\Hasher\ParametrizedHasher;

/**
 * ParametrizedHasher tests.
 *
 * @author kpacha <kpacha@gmail.com>
 */
class ParametrizedHasherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test hash with input as array.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testHashInputAsArray()
    {
        (new ParametrizedHasher())->hash(array('array'));
    }

    /**
     * Test hash.
     */
    public function testHash()
    {
        $this->assertEquals(
            '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',
            (new ParametrizedHasher())->hash('test')
        );
    }

    /**
     * Test hash with algorithm md5.
     */
    public function testHashMd5()
    {
        $this->assertEquals(
            md5('test'),
            (new ParametrizedHasher('md5'))->hash('test')
        );
    }

    /**
     * Test hasher constructor throws exception when an unknown algorithm is received.
     * 
     * @expectedException \InvalidArgumentException
     */
    public function testHasherWithUnknownAlgorithm()
    {
        new ParametrizedHasher('unknownAlgorithm');
        $this->fail('Exception expected!');
    }

    /**
     * Test hasher constructor accepts all known algorithm.
     */
    public function testHasherWithKnownAlgorithm()
    {
        foreach (hash_algos() as $algorithm) {
            $this->assertNotNull(new ParametrizedHasher($algorithm));
        }
    }
}
