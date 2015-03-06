<?php
namespace League\Uploads;

/**
 * DataSource
 *
 * This class abstracts the `$_FILES` superglobal and decouples
 * the League\Uploads component from its global environment.
 */
class DataSource implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * Array of uploaded file data in the same format as
     * the `$_FILES` superglobal array.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create new data source
     *
     * @param array $data Associative array that mimics the `$_FILES` superglobal array.
     *
     * @throws \InvalidArgumentException If data key is not a string
     * @throws \InvalidArgumentException If data key does not have array value
     * @throws \InvalidArgumentException If data key does not have `name` property
     * @throws \InvalidArgumentException If data key does not have `tmp_name` property
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $properties) {
            if (!is_string($key)) {
                throw new \InvalidArgumentException('Data key is not a string: ' . $key);
            }
            if (!is_array($properties)) {
                throw new \InvalidArgumentException('Data key does not have array value: ' . $key);
            }
            if (!isset($properties['name'])) {
                throw new \InvalidArgumentException('Data key does not have `name` property: ' . $key);
            }
            if (!isset($properties['tmp_name'])) {
                throw new \InvalidArgumentException('Data key does not have `tmp_name` property: ' . $key);
            }
            if (!isset($properties['error'])) {
                $properties['error'] = UPLOAD_ERR_OK;
            }

            $this->data[$key] = $properties;
        }
    }

    /**************************************************************************
     * Accessors
     *************************************************************************/

    public function getData()
    {
        return $this->data;
    }

    /**************************************************************************
     * ArrayAccess interface
     *************************************************************************/

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**************************************************************************
     * IteratorAggregate interface
     *************************************************************************/

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**************************************************************************
     * Countable interface
     *************************************************************************/

    public function count()
    {
        return count($this->data);
    }
}
