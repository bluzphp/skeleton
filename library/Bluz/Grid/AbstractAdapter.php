<?php
/**
 * Adapter
 *
 * @category Bluz
 * @package  Grid
 *
 * @author   Anton Shevchuk
 * @created  15.08.12 11:52
 */
namespace Bluz\Grid;

abstract class AbstractAdapter implements \Iterator, \Countable, \ArrayAccess
{
    /**
     * Iterator pointer.
     *
     * @var integer
     */
    protected $pointer = 0;

    /**
     * How many data rows there are.
     *
     * @var integer
     */
    protected $count = 0;

    /**
     * How many data rows w/out limits
     *
     * @var integer
     */
    protected $total;

    /**
     * Collection of data rows
     *
     * @var array
     */
    protected $data = array();

    /**
     * Setup adapter source
     *
     * @return boolean
     */
    abstract public function setSource();

    /**
     * Rewind the Iterator to the first element.
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return AbstractAdapter|void Fluent interface.
     */
    public function rewind()
    {
        $this->pointer = 0;
        return $this;
    }

    /**
     * Return the current element.
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return mixed current element from the collection
     */
    public function current()
    {
        if ($this->valid() === false) {
            return null;
        }
        // return the row object
        return $this->data[$this->pointer];
    }

    /**
     * Return the identifying key of the current element.
     * Similar to the key() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return int|\scalar
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Move forward to next element.
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return void
     */
    public function next()
    {
        ++$this->pointer;
    }

    /**
     * Check if there is a current element after calls to rewind() or next().
     * Used to check if we've iterated to the end of the collection.
     * Required by interface Iterator.
     *
     * @return bool False if there's nothing more to iterate over
     */
    public function valid()
    {
        return $this->pointer >= 0 && $this->pointer < $this->count;
    }

    /**
     * Returns the number of elements in the collection.
     *
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * Take the Iterator to position $position
     * Required by interface SeekableIterator.
     *
     * @param int $position the position to seek to
     * @return AbstractAdapter
     * @throws \OutOfBoundsException
     */
    public function seek($position)
    {
        $position = (int) $position;
        if ($position < 0 || $position >= $this->count) {
            throw new \OutOfBoundsException("Illegal index $position");
        }
        $this->pointer = $position;
        return $this;
    }


    /**
     * Check if an offset exists
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->data[(int) $offset]);
    }

    /**
     * Get the row for the given offset
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @throws \OutOfBoundsException
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $offset = (int) $offset;
        if ($offset < 0 || $offset >= $this->count) {
            throw new \OutOfBoundsException("Illegal index $offset");
        }
        $this->pointer = $offset;

        return $this->current();
    }

    /**
     * Does nothing
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Does nothing
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
    }
}
