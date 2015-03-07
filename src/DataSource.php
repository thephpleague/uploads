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
     * Uploaded file data in normalized form:
     *
     * [
     *     'foo' => [
     *         0 => [
     *             'name' => 'foo.txt',
     *             'tmp_name' => 'sdfsfdfsdd',
     *             'type' => 'text/plain',
     *             'size' => 300,
     *             'error' => UPLOAD_ERR_OK
     *         ]
     *     ]
     * ]
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create new data source
     *
     * @param array $data Associative array that matches the `$_FILES` superglobal.
     */
    public function __construct(array $data)
    {
        $this->data = $this->normalize($data);
    }

    /**
     * Normalize `$_FILES` array
     *
     * @param  array  $origFiles Original `$_FILES` array
     * @return array
     */
    protected function normalize(array $origFiles)
    {
        $newFiles = [];
        foreach ($origFiles as $fieldName => $fieldValue) {
            foreach ($fieldValue as $paramName => $paramValue) {
                foreach ((array)$paramValue as $index => $value) {
                    $newFiles[$fieldName][$index][$paramName] = $value;
                }
            }
        }

        return $newFiles;
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
