<?php

/*
 * This file is a part of the PHP Consistent Hashing Bundle.
 *
 * (c) 2013 Eduardo Oliveira
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ESO\CHashingBundle\Hasher;

/**
 * Interface of hasher.
 *
 * @author  Eduardo Oliveira <entering@gmail.com>
 */
interface HasherInterface
{
    /**
     * Hash a given string.
     *
     * Hash into 32bit address space.
     *
     * @param string $str String to hash.
     *
     * @return string The hash of $str.
     *
     * @throws \InvalidArgumentException
     */
    public function hash($str);
}
