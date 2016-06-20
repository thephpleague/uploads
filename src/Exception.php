<?php
namespace League\Uploads;

class Exception extends \RuntimeException
{
    /**
     * @var \League\Uploads\FileInfoInterface
     */
    protected $fileInfo;

    /**
     * Constructor
     *
     * @param string                    $message  The Exception message
     * @param \League\Uploads\FileInfoInterface $fileInfo The related file instance
     */
    public function __construct($message, \League\Uploads\FileInfoInterface $fileInfo = null)
    {
        $this->fileInfo = $fileInfo;

        parent::__construct($message);
    }

    /**
     * Get related file
     *
     * @return \League\Uploads\FileInfoInterface
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }
}
