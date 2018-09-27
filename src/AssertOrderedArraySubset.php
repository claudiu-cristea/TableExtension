<?php
/*
 * This file is based on code from PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LoversOfBehat\TableExtension;

use LoversOfBehat\TableExtension\Exception\NoArraySubsetException;
use LoversOfBehat\TableExtension\Exception\NoOrderedArraySubsetException;

/**
 * Asserts that an array has a specified ordered subset.
 *
 * All the entries of the subset must be found in the same subsequent order in the main array.
 *
 * @see \PHPUnit\Framework\Constraint\ArraySubset
 */
class AssertOrderedArraySubset extends AssertArraySubset
{

    /**
     * {@inheritdoc}
     */
    public function evaluate(array $other): void
    {
        $intersect = $this->arrayIntersectRecursive($other, $this->subset);

        // Intersection is keyed by the original array position. Re-order them.
        ksort($intersect);

        // Verify that the elements are positioned one after each other.
        $keys = array_keys($intersect);
        reset($keys);
        $start = current($keys);
        $expected_keys = range($start, count($keys) - 1 - $start);
        // Keys needs to be checked strict, or there is no order.
        if ($expected_keys !== $keys) {
            throw new NoOrderedArraySubsetException();
        }

        $result = $this->compare(array_values($intersect), $this->subset);

        if (!$result) {
            throw new NoArraySubsetException();
        }
    }

}
