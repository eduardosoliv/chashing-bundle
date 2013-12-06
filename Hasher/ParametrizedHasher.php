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

use ESO\CHashingBundle\Hasher\HasherInterface;

/**
 * ParametrizedHasher.
 *
 * @author kpacha <kpacha@gmail.com>
 */
class ParametrizedHasher implements HasherInterface
{
    const DEFAULT_ALGORITHM = 'sha256';

    /**
     * @var string
     */
    private $algorithm;

    /**
     * @param string $algorithm
     */
    public function __construct($algorithm = self::DEFAULT_ALGORITHM)
    {
        $this->validateAlgorithm($algorithm);
        $this->algorithm = $algorithm;
    }

    /**
     * Check if the received algorithm is available in the system
     *
     * @param string $algorithm
     *
     * @throws \InvalidArgumentException
     */
    protected function validateAlgorithm($algorithm)
    {
        $systemAlgorithms = hash_algos();
        if (!in_array($algorithm, $systemAlgorithms)) {
            throw new \InvalidArgumentException(
                sprintf('The "%s" algorithm is not available in this system.', $algorithm)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hash($str)
    {
        if (!is_string($str)) {
            throw new \InvalidArgumentException(
                'Cannot hash input not string.'
            );
        }

        return hash($this->algorithm, $str, false);
    }

}
