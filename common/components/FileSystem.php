<?php

/**
 * Class common\components\FileSystem
 *
 */

namespace common\components;

use Yii;

class FileSystem
{

    const KEY_TYPE = 'type';
    const KEY_NAME = 'name';
    const KEY_DATE = 'date';
    const KEY_SIZE = 'size';
    const KEY_EXT  = 'ext';

    const ASC  = 'asc';
    const DESC = 'desc';

    const TYPE_BOTH = 'both';
    const TYPE_DIR  = 'dir';
    const TYPE_FILE = 'file';


    /**
     * Callback for File Type and Name Filters
     *
     * @var     object
     * @access  protected
     *
     **/
    protected $filterCallback = false;

    /**
     * Stores the last directory listing total size in bytes
     *
     * @var     int
     * @access  protected
     *
     **/
    protected $lastSize = 0;

    /**
     * Stores the last directory listing total number of files
     *
     * @var     int
     * @access  protected
     *
     **/
    protected $lastItemCount = 0;


    /**
     * Sets the Filter Callback
     *
     * @access public
     * @param  callable  $obj
     * @return void
     */
    public function setFilterCallback($obj)
    {
        $this->filterCallback = $obj;
    }


    /**
     * Order the results of a directory listing
     *
     * @access protected
     * @param  array   $array
     * @param  string  $type
     * @param  string  $by
     * @param  string  $direction
     * @return array
     */
    protected function sort($array, $type = self::TYPE_BOTH, $by = self::KEY_NAME, $direction = self::ASC)
    {
        $returnArray = array();
        if (count($array) > 0) {
            // The Sorting
            $tempArray = array();

            foreach ($array as $key => $value) {
                $tempArray[$key] = $value[$by];
            }

            natcasesort($tempArray);
            if ($direction == self::DESC) {
                $tempArray = array_reverse($tempArray, true);
            }

            foreach ($tempArray as $key => $value) {
                $returnArray[] = $array[$key];
            }

            // If sorting by name, lets separate the files from the dirs.
            if ($by == self::KEY_NAME && $type == self::TYPE_BOTH) {
                $files = array();
                $dirs  = array();

                foreach ($returnArray as $value) {
                    if ($value[self::KEY_TYPE] == self::TYPE_FILE) {
                        $files[] = $value;
                    } elseif ($value[self::KEY_TYPE] == self::TYPE_DIR) {
                        $dirs[] = $value;
                    }
                }

                if ($direction == self::DESC) {
                    $returnArray = array_merge($files, $dirs);
                } else {
                    $returnArray = array_merge($dirs, $files);
                }
            }
        }

        return $returnArray;
    }


    /**
     * Return the listing of a directory
     *
     * @access public
     * @param  string  $directory
     * @param  string  $type
     * @param  string  $order
     * @param  string  $direction
     * @param  bool    $limit
     * @param  array   $fileExtensions
     * @return array
     * @throws \Exception
     */
    public function getListing($directory, $type = self::TYPE_BOTH, $order = self::KEY_NAME, $direction = self::ASC, $limit = false, $fileExtensions = array())
    {
        // Get the contents of the dir
        $listing   = array();
        $directory = rtrim($directory, '/');

        // Check Dir
        if (!is_dir($directory)) {
            throw new \Exception('Directory does not exist: '.$directory);
        }

        // Get Raw Listing
        $directoryHandle = opendir($directory);
        while (false !== ($file = readdir($directoryHandle))) {
            if (substr($file, 0, 1) != '.') {
                // skip anything that starts with a '.' i.e.:('.', '..', or any hidden file)
                // Directories
                if (is_dir($directory . '/' . $file) && ($type == self::TYPE_BOTH || $type == self::TYPE_DIR)) {
                    $listing[] = array(
                        self::KEY_TYPE => self::TYPE_DIR,
                        self::KEY_NAME => $file,
                        self::KEY_DATE => filemtime($directory.'/'.$file),
                        self::KEY_SIZE => filesize($directory.'/'.$file),
                        self::KEY_EXT  => ''
                    );
                // Files
                } elseif (is_file($directory.'/'.$file) && ($type == self::TYPE_BOTH || $type == self::TYPE_FILE)) {
                    if (!count($fileExtensions) || in_array(Util::getExtension($file), $fileExtensions)) {
                        $listing[] = array(
                            self::KEY_TYPE => self::TYPE_FILE,
                            self::KEY_NAME => $file,
                            self::KEY_DATE => filemtime($directory.'/'.$file),
                            self::KEY_SIZE => filesize($directory.'/'.$file),
                            self::KEY_EXT  => Util::getExtension($file)
                        );
                    }
                }
            }

            // Impose Limit, if specified
            if ($limit && count($listing) >= $limit) {
                break;
            }
        }

        closedir($directoryHandle);

        // Sorting
        $listing = $this->sort($listing, $type, $order, $direction);

        // Callbacks
        if ($this->filterCallback) {
            $listing = call_user_func($this->filterCallback, $listing);
        }

        // Total Size
        $totalSize = 0;

        foreach ($listing as $item) {
            $totalSize += $item[self::KEY_SIZE];
        }

        $this->lastSize = $totalSize;

        // Item Count
        $this->lastItemCount = count($listing);

        // Done
        return $listing;
    }


    /**
     * Return the last listing size in bytes
     *
     * @access public
     * @return int
     */
    public function getLastSize()
    {
        return $this->lastSize;
    }


    /**
     * Return the last listing count of items
     *
     * @access public
     * @return int
     */
    public function getLastItemCount()
    {
        return $this->lastItemCount;
    }
}
