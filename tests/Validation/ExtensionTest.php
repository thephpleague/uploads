<?php
class ExtensionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->assetsDirectory = dirname(__DIR__) . '/assets';
    }

    public function testValidExtension()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.txt', 'foo.txt');
        $validation = new \League\Uploads\Validation\Extension('txt');
        $validation->validate($file); // <-- SHOULD NOT throw exception
    }

    /**
     * @expectedException \League\Uploads\Exception
     */
    public function testInvalidExtension()
    {
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo_wo_ext', 'foo_wo_ext');
        $validation = new \League\Uploads\Validation\Extension('txt');
        $validation->validate($file); // <-- SHOULD throw exception
    }
}
