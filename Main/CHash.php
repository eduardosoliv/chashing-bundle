<?php

/*
 * This file is a part of the PHP Consistent Hashing Bundle.
 *
 * (c) 2013 Eduardo Oliveira
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ESO\CHashingBundle\Main;

use ESO\CHashingBundle\Main\Targets;

/**
 * CHasher.
 *
 * @author  Eduardo Oliveira <entering@gmail.com>
 */
class CHash
{
    /**
     * Targets.
     *
     * @var \ESO\CHashingBundle\Main\Targets
     */
    private $targets;

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
        $this->targets = new Targets($hasher, $replicas);
    }

    /**
     * Return targets.
     *
     * @return \ESO\CHashingBundle\Main\Targets
     */
    public function targets()
    {
        return $this->targets();
    }

}
