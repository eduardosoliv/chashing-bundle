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

/**
 * Targets.
 *
 * @author  Eduardo Oliveira <entering@gmail.com>
 */
class Targets
{
    /**
     * The number of positions that each target will be hash to.
     *
     * @var integer
     */
    private $replicas = 64;

    /**
     * Hasher.
     *
     * @var \ESO\CHashingBundle\ESO\CHashingBundle\Hasher\HasherInterface
     */
    private $hasher;

    /**
     * Map of targets to positions that target is hashed to.
     *
     * array(
     *     'targetName1' => array(position1, position2, ...),
     *     'targetName2' => ...
     * )
     *
     * @var array
     */
    private $targetsPositions = array();

    /**
     * Map of positions to targets.
     *
     * array(
     *     'position1' => 'targetName1',
     *     'position2' => 'targetName2',
     *     ...
     * )
     *
     * @var array
     */
    private $positionsTargets = array();

    /**
     * Internal map of positions to targets is sorted.
     *
     * @var boolean
     */
    private $positionsTargetsSorted = false;

    /**
     * Internal counter of targets.
     *
     * @var integer
     */
    private $targetCount = 0;

    /**************************************************************************
     * Constructor.
     *************************************************************************/

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

    /**************************************************************************
     * Add/del/has targets.
     *************************************************************************/

    /**
     * Add target.
     *
     * @param string  $name   Name.
     * @param integer $weight Weight.
     *
     * @throws \InvalidArgumentException Arguments invalid.
     * @throws \Exception                Already present on mapping.
     */
    public function add($name, $weight = 1)
    {
        // validations
        if (!$this->validateName($name)) {
            throw new \InvalidArgumentException('Invalid target name.');
        }
        if (!$this->validateWeight($weight)) {
            throw new \InvalidArgumentException('Weight needs to be a positive integer.');
        }
        if ($this->has($name)) {
            throw new \Exception("Target '$name' already present on mapping.");
        }

        // add target to map
        $this->targetsPositions[$name] = array();

        $max = $this->getNumberReplicas() * $weight;
        for ($i = 0; $i < $max;  ++$i) {
            $position = $this->getHasher()->hash($name . $i); // concatenate name of target with number
            $this->positionsTargets[$position] = $name; // add target to array of positions
            $this->targetsPositions[$name][] = $position; // add position to the target
        }

        // not sorted anymore
        $this->positionsTargetsSorted = false;

        // one more target
        ++$this->targetCount;
    }

    /**
     * Add multi targets.
     *
     * array(
     *     'targetName1' => weight1,
     *     'targetName2' => weight2
     * )
     *
     * @param array $targets Targets.
     */
    public function addMulti(array $targets)
    {
        foreach ($targets as $name => $weight) {
            $this->add($name, $weight);
        }
    }

    /**
     * Delete target.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function del($name)
    {
        // validations
        if (!$this->validateName($name)) {
            throw new \InvalidArgumentException('Invalid target name.');
        }
        if (!$this->has($name)) {
            throw new \Exception("Target '$name' not present on mapping.");
        }

        foreach ($this->targetsPositions[$name] as $position) {
            unset($this->positionsTargets[$position]);
        }

        unset($this->targetsPositions[$name]);

        --$this->targetCount;
    }

    /**
     * Delete multiple targets.
     *
     * @param array $targets Targets as array('targetName1', 'targetName2', ...)
     */
    public function delMulti(array $targets)
    {
        foreach ($targets as $name) {
            $this->del($name);
        }
    }

    /**
     * Check if given target name is already present on targets map.
     *
     * @param string $name
     *
     * @return boolean True if name is already present at targets.
     *
     * @throws \InvalidArgumentException
     */
    public function has($name)
    {
        // validations
        if (!$this->validateName($name)) {
            throw new \InvalidArgumentException('Invalid target name.');
        }

        return isset($this->targetsPositions[$name]);
    }

    /**************************************************************************
     * Other methods.
     *************************************************************************/

    /**
     * Get positions to targets mapping.
     *
     * @return array
     */
    public function &getPositionsTargets()
    {
        return $this->positionsTargets;
    }

    /**
     * Number of replicas.
     *
     * @return integer
     */
    public function getNumberReplicas()
    {
        return $this->replicas;
    }

    /**
     * Return hasher.
     *
     * @return \ESO\CHashingBundle\ESO\CHashingBundle\Hasher\HasherInterface
     */
    public function getHasher()
    {
        return $this->hasher;
    }

    /**
     * Sort position targets.
     */
    public function sortPositionTargets()
    {
        // check if sorted
        if (!$this->positionsTargetsSorted) {
            ksort($this->positionsTargets, SORT_REGULAR);
            $this->positionsTargetsSorted = true;
        }
    }

    /**
     * Returns targets count.
     *
     * @return integer
     */
    public function getTargetsCount()
    {
        return $this->targetCount;
    }

    /**************************************************************************
     * Validations
     *************************************************************************/

    /**
     * Validate name of target.
     *
     * @param string $name Name.
     *
     * @return boolean True if name is valid.
     */
    private function validateName($name)
    {
        return (is_string($name) && $name != '');
    }

    /**
     * Validate weight.
     *
     * @param integer $weight Weight.
     *
     * @return boolean True if weight is valid.
     */
    private function validateWeight($weight)
    {
        return (is_int($weight) && $weight >= 1);
    }
}
