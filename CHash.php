<?php

/*
 * This file is a part of the PHP Consistent Hashing Bundle.
 *
 * (c) 2013 Eduardo Oliveira
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ESO\CHashingBundle;

/**
 * CHasher.
 *
 * @author  Eduardo Oliveira <entering@gmail.com>
 */
class CHash
{
    private $replicas = 64;

    /**
     * Constructor.
     *
     * @param \ESO\CHashingBundle\ESO\CHashingBundle\Hasher\HasherInterface $hasher   Hasher algorithm.
     * @param integer                                                       $replicas Number of positions   to hash each target to.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\ESO\CHashingBundle\Hasher\HasherInterface $hasher, $replicas = null)
    {
        // validations
        if ($replicas !== null && (!is_int($replicas) || $replicas < 1)) {
            throw new \InvalidArgumentException('Number of replicas needs to be a positive integer.');
        }

        // assign
        $this->hasher = $hasher;
        if ($replicas !== null) {
            $this->replicas = $replicas;
        }
    }
}
