<?php

class DimensionsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->assetsDirectory = dirname(__DIR__) . '/assets';
    }

    public function testWidthAndHeight()
    {
        $dimensions = new \League\Uploads\Validation\Dimensions(100, 100);
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.png', 'foo.png');
        $dimensions->validate($file);
    }

    /**
     * @expectedException \League\Uploads\Exception
     */
    public function testWidthDoesntMatch()
    {
        $dimensions = new \League\Uploads\Validation\Dimensions(200, 100);
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.png', 'foo.png');
        $dimensions->validate($file);
    }

    /**
     * @expectedException \League\Uploads\Exception
     */
    public function testHeightDoesntMatch()
    {
        $dimensions = new \League\Uploads\Validation\Dimensions(100, 200);
        $file = new \League\Uploads\FileInfo($this->assetsDirectory . '/foo.png', 'foo.png');
        $dimensions->validate($file);
    }
}