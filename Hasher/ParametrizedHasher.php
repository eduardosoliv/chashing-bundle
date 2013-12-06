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
     * @var String
     */
    private $algorithm;

    public function __construct($algorithm = self::DEFAULT_ALGORITHM)
    {
        $this->validateAlgorithm($algorithm);
        $this->algorithm = $algorithm;
    }

    /**
     * Check if the received algorithm is available in the system
     * @param String $algorithm
     * @throws \InvalidArgumentException
     */
    private function validateAlgorithm($algorithm)
    {
        $systemAlgrithms = hash_algos();
        if (!in_array($algorithm, $systemAlgrithms)) {
            throw new \InvalidArgumentException(
                    "The '$algorithm' algorithm is not available in this system."
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
