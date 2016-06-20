<?php
class SizeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->assetsDirectory = dirname(__DIR__) . '/assets';
    }

    public function testValidFileSize()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.txt', 'foo.txt');
        $validation = new \League\Uploads\Validation\Size(500);
        $validation->validate($file); // <-- SHOULD NOT throw exception
    }

    public function testValidFileSizeWithHumanReadableArgument()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.txt', 'foo.txt');
        $validation = new \League\Uploads\Validation\Size('500B');
        $validation->validate($file); // <-- SHOULD NOT throw exception
    }

    /**
     * @expectedException \League\Uploads\Exception
     */
    public function testInvalidFileSize()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.txt', 'foo.txt');
        $validation = new \League\Uploads\Validation\Size(400);
        $validation->validate($file); // <-- SHOULD throw exception
    }

    /**
     * @expectedException \League\Uploads\Exception
     */
    public function testInvalidFileSizeWithHumanReadableArgument()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.txt', 'foo.txt');
        $validation = new \League\Uploads\Validation\Size('400B');
        $validation->validate($file); // <-- SHOULD throw exception
    }
}
