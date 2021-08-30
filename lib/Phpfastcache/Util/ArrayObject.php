<?php
/**
 *
 * This file is part of Phpfastcache.
 *
 * @license MIT License (MIT)
 *
 * For full copyright and license information, please see the docs/CREDITS.txt and LICENCE files.
 *
 * @author Georges.L (Geolim4)  <contact@geolim4.com>
 * @author Contributors  https://github.com/PHPSocialNetwork/phpfastcache/graphs/contributors
 */

declare(strict_types=1);

namespace Phpfastcache\Util;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Class ArrayObject
 * @package phpFastCache\Util
 */
class ArrayObject implements ArrayAccess, Iterator, Countable
{
    private array $array;

    private int $position = 0;

    /**
     * @param $args
     * ArrayObject constructor.
     */
    public function __construct(...$args)
    {
        $this->array = (count($args) === 1 && is_array($args[0]) ? $args[0] : $args);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->array[$this->position];
    }

    /**
     *
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->offsetExists($this->position);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->array);
    }

    /**
     *
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->array);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->array[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        // NOTE: THIS IS THE FIX FOR THE ISSUE "Indirect modification of overloaded element of SplFixedArray has no effect"
        // NOTE: WHEN APPENDING AN ARRAY (E.G. myArr[] = 5) THE KEY IS NULL, SO WE TEST FOR THIS CONDITION BELOW, AND VOILA

        if ($offset === null) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * @param array $array
     * @return self
     */
    public function mergeArray(array $array): self
    {
        $this->array = array_merge($this->array, $array);

        return $this;
    }

    /**
     * @return array
     */
    protected function &getArray(): array
    {
        return $this->array;
    }
}
