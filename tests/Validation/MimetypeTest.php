<?php
class MimetypeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->assetsDirectory = dirname(__DIR__) . '/assets';
    }

    public function testValidMimetype()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.txt', 'foo.txt');
        $validation = new \League\Uploads\Validation\Mimetype(array('text/plain'));
        $validation->validate($file); // <-- SHOULD NOT throw exception
    }

    /**
     * @expectedException \League\Uploads\Exception
     */
    public function testInvalidMimetype()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.txt', 'foo.txt');
        $validation = new \League\Uploads\Validation\Mimetype(array('image/png'));
        $validation->validate($file); // <-- SHOULD throw exception
    }
}
