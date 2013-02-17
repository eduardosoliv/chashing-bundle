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

use ESO\CHashingBundle\Hasher\Md5;

/**
 * Md5 tests.
 *
 * @author  Eduardo Oliveira <entering@gmail.com>
 */
class Md5Test extends \PHPUnit_Framework_TestCase
{
    /**
     * Test hash with input as array.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testHashInputAsArray()
    {
    (new Md5())->hash(array('array'));
    }

    /**
     * Test hash.
     */
    public function testHash()
    {
    $this->assertEquals(
            '098f6bcd',
            (new Md5())->hash('test')
        );
    }
}
