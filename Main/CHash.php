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

use ESO\CHashingBundle\Hasher\HasherInterface;
use ESO\CHashingBundle\Main\Targets;

/**
 * CHasher.
 *
 * @author Eduardo Oliveira <entering@gmail.com>
 */
class CHash
{
    /**
     * Targets.
     *
     * @var \ESO\CHashingBundle\Main\Targets
     */
    private $targets;

    /**************************************************************************
     * Constructor.
     *************************************************************************/

    /**
     * Constructor.
     *
     * @param HasherInterface $hasher   Hasher algorithm.
     * @param integer         $replicas Number of positions to hash each target.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(HasherInterface $hasher, $replicas = null)
    {
        $this->targets = new Targets($hasher, $replicas);
    }

    /**************************************************************************
     * Lookup
     *************************************************************************/

    /**
     * Lookup.
     *
     * @param string  $key          Key.
     * @param integer $targetsCount Targets count.
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function lookup($key, $targetsCount = 1)
    {
        $this->validateLookup($key, $targetsCount);

        $positionsTargets = &$this->targets()->getPositionsTargets();

        // initialize result
        $resTargets = array();
        $resTargetsCount = 0;

        // sortt
        $this->targets->sortPositionTargets();

        // hash key
        $keyPosition = $this->targets()->getHasher()->hash($key);

        // at start don't collect any target
        $collect = false;

        // search values above the key position
        foreach ($positionsTargets as $position => $targetName) {
            // just start collecting after passing key position
            if (!$collect && $position > $keyPosition) {
                $collect = true;
            }

            // avoid collection duplicates, using isset just due to performance
            if ($collect && !isset($resTargets[$targetName])) {
                // add target and increment counter
                $resTargets[$targetName] = $targetName;
                ++$resTargetsCount;

                // break when enough results or list exhausted
                if ($resTargetsCount == $targetsCount ||
                    $resTargetsCount == $this->targets()->getTargetsCount()) {
                    break;
                }
            }
        }

        return array_values($resTargets);
    }

    /**************************************************************************
     * Targets.
     *************************************************************************/

    /**
     * Return targets.
     *
     * @return \ESO\CHashingBundle\Main\Targets
     */
    public function targets()
    {
        return $this->targets;
    }

    /**************************************************************************
     * Validations
     *************************************************************************/

    /**
     * Validate lookup.
     *
     * @param string  $key          Key.
     * @param integer $targetsCount Targets count.
     *
     * @throws \InvalidArgumentException
     */
    private function validateLookup($key, $targetsCount)
    {
        /// validations
        if (!$this->validateKey($key)) {
            throw new \InvalidArgumentException('Invalid key.');
        }
        if (!$this->validateTargetsCount($targetsCount)) {
            throw new \InvalidArgumentException(
                'Targets count needs to be a positive integer.'
            );
        }
    }

    /**
     * Validate key.
     *
     * @param string $key Key.
     *
     * @return boolean True if key is valid.
     */
    private function validateKey($key)
    {
        return (is_string($key) && $key != '');
    }

    /**
     * Validate targets count.
     *
     * @param integer $targetsCount Targets count.
     *
     * @return boolean True if targets count is valid.
     */
    private function validateTargetsCount($targetsCount)
    {
        return (is_int($targetsCount) && $targetsCount >= 1);
    }

}
