<?php
class FileSystemTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup (each test)
     */
    public function setUp()
    {
        // Path to test assets
        $this->assetsDirectory = dirname(__DIR__) . '/assets';

        // Reset $_FILES superglobal
        $_FILES['foo'] = array(
            'name' => 'foo.txt',
            'tmp_name' => $this->assetsDirectory . '/foo.txt',
            'error' => 0
        );
    }

    public function testInstantiationWithValidDirectory()
    {
        try {
            $storage = $this->getMock(
                '\League\Uploads\Storage\FileSystem',
                array('upload'),
                array($this->assetsDirectory)
            );
        } catch(\InvalidArgumentException $e) {
            $this->fail('Unexpected argument thrown during instantiation with valid directory');
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiationWithInvalidDirectory()
    {
        $storage = $this->getMock(
            '\League\Uploads\Storage\FileSystem',
            array('upload'),
            array('/foo')
        );
    }

    /**
     * Test won't overwrite existing file
     * @expectedException \League\Uploads\Exception
     */
    public function testWillNotOverwriteFile()
    {
        $storage = new \League\Uploads\Storage\FileSystem($this->assetsDirectory, false);
        $storage->upload(new \League\Uploads\FileInfo('foo.txt', dirname(__DIR__) . '/assets/foo.txt'));
    }

    /**
     * Test will overwrite existing file
     */
    public function testWillOverwriteFile()
    {
        $storage = $this->getMock(
            '\League\Uploads\Storage\FileSystem',
            array('moveUploadedFile'),
            array($this->assetsDirectory, true)
        );
        $storage->expects($this->any())
                ->method('moveUploadedFile')
                ->will($this->returnValue(true));

        $fileInfo = $this->getMock(
            '\League\Uploads\FileInfo',
            array('isUploadedFile'),
            array(dirname(__DIR__) . '/assets/foo.txt', 'foo.txt')
        );
        $fileInfo->expects($this->any())
             ->method('isUploadedFile')
             ->will($this->returnValue(true));

        $storage->upload($fileInfo);
    }
}
