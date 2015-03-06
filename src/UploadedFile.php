<?php
namespace League\Uploads;

/**
 * UploadedFile
 */
class UploadedFile extends \SplFileInfo
{
    /**
     * Data source for uploaded file data
     *
     * @var DataSource
     */
    protected static $dataSource;

    /**
     * File extension
     */
    protected $extension;

    /**
     * File mimetype
     *
     * @var string
     */
    protected $mimetype;

    /**
     * File name
     *
     * @var string
     */
    protected $name;

    /**
     * Original name provided by HTTP client
     *
     * @var null|string
     */
    protected $originalName;

    /**************************************************************************
     * Constructors
     *************************************************************************/

    /**
     * Create new uploaded file
     *
     * @param  string $path         Local filesystem path to uploaded file
     * @param  string $originalName The original file name provided by the HTTP client
     *
     * @throws \InvalidArgumentException If file path is not a valid uploaded file
     */
    public function __construct($path, $originalName = null)
    {
        if (!$this->isUploadedFile($path)) {
            throw new \InvalidArgumentException('File path is not a valid uploaded file');
        }
        $this->originalName = $originalName;

        parent::__construct($path);
    }

    /**
     * Create new uploaded file from `$_FILES` superglobal data
     *
     * @param  string $name The uploaded file's key in the `$_FILES` superglobal
     *
     * @return self
     */
    public static function createFromGlobals($name)
    {
        if (static::$dataSource === null) {
            static::$dataSource = new DataSource($_FILES);
        }

        if (!isset(static::$dataSource[$name])) {
            throw new \InvalidArgumentException('File does not exist in datasource.');
        }

        if (static::$dataSource[$name]['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('File upload error', static::$dataSource[$name]['error']);
        }

        return new static(static::$dataSource[$name]['tmp_name'], static::$dataSource[$name]['name']);
    }

    /**
     * Set uploaded file data source
     *
     * @param DataSource $source
     */
    public static function setDataSource(DataSource $source)
    {
        static::$dataSource = $source;
    }

    /**************************************************************************
     * Helpers
     *************************************************************************/

    /**
     * Is path a valid uploaded file?
     *
     * We separate this test into its own method so we can easily
     * stub this method in unit tests.
     *
     * @param  string $path Local filesystem file path
     *
     * @return bool
     */
    public function isUploadedFile($path)
    {
        return is_uploaded_file($path);
    }

    /**************************************************************************
     * Properties
     *************************************************************************/

    /**
     * Get file extension
     *
     * @return string
     */
    public function getExtension()
    {
        if ($this->extension === null) {
            $hit = Mimetypes::getExtensionForMimetype($this->getMimetype());
            $this->extension = $hit ? $hit : parent::getExtension();
        }

        return $this->extension;
    }

    /**
     * Get file mimetype
     *
     * @return string
     */
    public function getMimetype()
    {
        if ($this->mimetype === null) {
            // NOTE: We can probably be smarter about this
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $this->mimetype = $finfo->file($this->getRealPath());
            finfo_close($finfo);
        }

        return $this->mimetype;
    }

    /**
     * Get human readable file size in a given unit
     *
     * @param  string $unit     The desired unit
     * @param  int    $decimals The number of desired decimals
     *
     * @return float
     */
    public function getHumanSize($unit = 'B', $decimals = 2)
    {
        $sz = 'BKMGTP';
        $bytes = $this->getSize();
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getName()
    {
        if ($this->name === null) {
            $this->name = $this->getBasename('.' . $this->getExtension());
        }

        return $this->name;
    }

    /**
     * Set new file name
     *
     * @param string $name File name without extension
     *
     * @throws \InvalidArgumentException If arguent is not a string
     * @throws \InvalidArgumentException If argument is not a valid file name
     */
    public function setName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('File name must be a string');
        }
        $filename = pathinfo($name, PATHINFO_FILENAME);
        if (empty($filename)) {
            throw new \InvalidArgumentException('File name is invalid');
        }
        $this->name = $filename;
    }

    /**
     * Return original file name provided by HTTP client
     *
     * @return null|string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }
}
