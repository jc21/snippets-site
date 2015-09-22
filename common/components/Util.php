<?php

/**
 * Class common\components\Util
 *
 */

namespace common\components;

use Yii;

class Util
{

    /**
     * getExtension
     * Returns the extension of a file, lowercase
     *
     * @access static
     * @param  string  $file
     * @return string
     */
    public static function getExtension($file)
    {
        if (strpos($file, '.') !== false) {
            $tempext = strtolower(substr($file, strrpos($file, '.') + 1, strlen($file) - strrpos($file, '.')));
            return strtolower(trim($tempext, '/'));
        }

        return '';
    }


    /**
     * removeExtension
     * Returns the main name of a File, minus the extension
     *
     * @access static
     * @return string
     */
    public static function removeExtension($file)
    {
        if (strpos($file, '.') !== false) {
            $name = substr($file, 0, strrpos($file, '.'));
            return trim($name, '/');
        }

        return $file;
    }


    /**
     * getReadableSize
     * Returns the human readable representation of a file size
     *
     * @access static
     * @param  int    $bytes
     * @return string
     */
    public static function getReadableSize($bytes)
    {
        if ($bytes >= 1024) {
            $sizeKb = round(($bytes / 1024), 0);
            if ($sizeKb >= 1024) {
                $sizeMb = round(($bytes / 1024 / 1024), 2);
                if ($sizeMb >= 1024) {
                    $sizeGb     = round(($bytes / 1024 / 1024 / 1024), 2);
                    $sizeReturn = number_format($sizeGb, 2, '.', ',') . ' gig';
                } else {
                    $sizeReturn = number_format($sizeMb, 2, '.', ',') . ' mb';
                }
            } else {
                $sizeReturn = number_format($sizeKb, 0, '.', ',') . ' kb';
            }
        } else {
            $sizeReturn = $bytes.' b';
        }

        return $sizeReturn;
    }


    /**
     * truncate
     *
     * @access public
     * @param  string $string The string to truncate
     * @param  int    $length The total length at which to truncate
     * @return string
     */
    public static function truncate($string, $length)
    {
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length - 3);

            // find the last link, as it's the one (if any) that will
            // be truncated
            $linkStart = strripos($string, '<a');
            if ($linkStart !== false) {
                $pattern = '/(<a href="((http(s?):\/\/){1}((\w+\.){1,})\w{2,}(\/?)[\w\d;\|:@&=\$\-_\.\+!\*\'\(\),%#\?\/]+)"(\s[^<>]*)?>)(.*)<\/a>/i';
                if (!preg_match($pattern, substr($string, $linkStart))) {
                    // unclosed
                    $stringPre  = substr($string, 0, $linkStart);
                    $stringPost = substr($string, $linkStart);
                    $stringPost = preg_replace('/>(.+)/i', '>$1</a>', $stringPost);
                    // remove partial
                    $string = (strpos($stringPost, '</a>') !== false) ? $stringPre . $stringPost : $stringPre;
                }
            }

            //check if the word is within 20 chars of the end length, and if not, forget about truncating on a word
            $theword = strrpos($string, ' ');
            if ($theword > (strlen($string) - 20)) {
                $string = substr($string, 0, strrpos($string, ' ')) . '...';
            } else {
                $string = $string . '...';
            }
        }

        return $string;
    }


    /**
     * Sort multi dimensional array (or object) by key/property value
     *
     * @access public
     * @param  array  $array
     * @param  string $keyName
     * @param  string $direction
     * @return array
     */
    public static function sortByKey($array, $keyName, $direction = 'asc')
    {
        $returnArray = array();
        if (count($array) > 0) {
            // The Sorting
            $tempArray = array();
            foreach ($array as $key => $value) {
                if (is_object($value)) {
                    // Must have a getter
                    $method          = 'get' . ucfirst($keyName);
                    $tempArray[$key] = $value->$method();
                } else {
                    $tempArray[$key] = $value[$keyName];
                }
            }

            natcasesort($tempArray);

            if ($direction != 'asc') {
                $tempArray = array_reverse($tempArray, true);
            }

            $i = 0;
            foreach ($tempArray as $key => $value) {
                $returnArray[$i] = $array[$key];
                ++$i;
            }
        }

        return $returnArray;
    }


    /**
     * getPagination
     *
     * @access public
     * @param  int     $offset
     * @param  int     $recordCount
     * @param  int     $rowsPerPage
     * @param  string  $offsetVar
     * @return string
     */
    public static function getPagination($offset, $recordCount, $rowsPerPage, $offsetVar = 'offset')
    {
        $html             = '';
        $numPages         = $rowsPerPage != 0 ? ceil($recordCount / $rowsPerPage) : 0;
        $currentPage      = $rowsPerPage != 0 ? ceil($offset / $rowsPerPage) : 0;
        $paginationOffset = 0;

        if ($rowsPerPage > 0 && $offset > 0 && ($offset % $rowsPerPage) > 0) {
            // Allow for an arbitrary offset - (The first page contains less than $rowsPerPage)
            $rowsOnFirstPage  = ($offset % $rowsPerPage);
            $paginationOffset = $rowsOnFirstPage - $rowsPerPage; // negative
            $numPages         = ceil(($recordCount - $rowsOnFirstPage) / $rowsPerPage) + 1;
            $currentPage      = ($offset < $rowsOnFirstPage) ? 0 : ceil(($offset - $rowsOnFirstPage) / $rowsPerPage) + 1;
        }

        if ($numPages > 1) {
            $html .= '<nav id="pagination">' . PHP_EOL;
            $html .= '  <ul class="pagination">' . PHP_EOL;

            // Get the "Previous" links if required
            if ($currentPage > 0) {
                $url = new Url();
                $url->setVar($offsetVar, ($offset - $rowsPerPage < 0 ? '0' : $offset - $rowsPerPage));
                $html .= '<li><a href="'.$url->get().'" title="Previous Page">&laquo;</a></li>' . PHP_EOL;
            } else {
                $html .= '<li class="disabled"><a href="#" title="Previous Page">&laquo;</a></li>'.PHP_EOL;
            }


            // Create the page numbers
            $segmentSize = 5;
            $navarray    = array();

            // If more than 15 Pages, print a set of page link segments with separators
            if ($numPages > 15) {
                // beginning
                for ($pageNum = 1; $pageNum <= $segmentSize; $pageNum++) {
                    $newOffset = ($pageNum * $rowsPerPage) - $rowsPerPage + ($pageNum > 1 ? $paginationOffset : 0);
                    $c         = '';

                    if ($currentPage + 1 == $pageNum) {
                        $c = ' class="active"';
                    }

                    $url = new Url();
                    $url->setVar($offsetVar, $newOffset);
                    $navarray[$pageNum] = '<li'.$c.'><a href="'.$url->get().'" class="number" title="'.$pageNum.'">'.$pageNum.'</a></li>'.PHP_EOL;
                }

                // middle
                $startPageNum = ($currentPage + 1 - floor($segmentSize / 2));
                $endPageNum   = ($currentPage + ceil($segmentSize / 2));
                for ($pageNum = $startPageNum; $pageNum <= $endPageNum; $pageNum++) {
                    if ($pageNum > 0 && $pageNum <= $numPages) {
                        $newOffset = ($pageNum * $rowsPerPage) - $rowsPerPage + ($pageNum > 1 ? $paginationOffset : 0);
                        $c         = '';

                        if ($currentPage + 1 == $pageNum) {
                            $c = ' class="active"';
                        }

                        $url = new Url();
                        $url->setVar($offsetVar, $newOffset);
                        $navarray[$pageNum] = '<li'.$c.'><a href="'.$url->get().'" class="number" title="'.$pageNum.'">'.$pageNum.'</a></li>'.PHP_EOL;
                    }
                }

                // end
                for ($pageNum = ($numPages - $segmentSize + 1); $pageNum <= $numPages; $pageNum++) {
                    if ($pageNum > 0 && $pageNum <= $numPages) {
                        $newOffset = ($pageNum * $rowsPerPage) - $rowsPerPage + ($pageNum > 1 ? $paginationOffset : 0);
                        $c         = '';

                        if ($currentPage + 1 == $pageNum) {
                            $c = ' class="active"';
                        }

                        $url = new Url();
                        $url->setVar($offsetVar, $newOffset);
                        $navarray[$pageNum] = '<li'.$c.'><a href="'.$url->get().'" class="number" title="'.$pageNum.'">'.$pageNum.'</a></li>'.PHP_EOL;
                    }
                }
            } else {
                // Less than 15 Pages, print everything
                for ($pageNum = 1; $pageNum <= $numPages; $pageNum++) {
                    $newOffset = ($pageNum * $rowsPerPage) - $rowsPerPage + ($pageNum > 1 ? $paginationOffset : 0);
                    $c         = '';

                    if ($currentPage + 1 == $pageNum) {
                        $c = ' class="active"';
                    }

                    $url = new Url();
                    $url->setVar($offsetVar, $newOffset);
                    $navarray[$pageNum] = '<li'.$c.'><a href="'.$url->get().'" class="number" title="'.$pageNum.'">'.$pageNum.'</a></li>'.PHP_EOL;
                }
            }

            ksort($navarray);

            foreach ($navarray as $pageidx => $link) {
                $html .= $link;
                // Add separator if there are "gaps" between the "current" and "next" page index
                if (!isset($navarray[($pageidx + 1)]) && $pageidx != $numPages) {
                    $html .= '<li class="disabled gap"><a href="#">...</a></li>';
                }
            }

            // Next records
            $nextOffset = ($offset + $rowsPerPage);

            // Get the "Next" link if required
            if ($nextOffset < $recordCount) {
                $url = new Url();
                $url->setVar($offsetVar, $nextOffset);
                $html .= '<li><a href="'.$url->get().'" title="Next Page">&raquo;</a></li>'.PHP_EOL;
            } else {
                $html .= '<li class="disabled"><a href="#" title="Next Page">&raquo;</a></li>'.PHP_EOL;
            }

            $html .= '  </ul>'.PHP_EOL;
            $html .= '</nav>'.PHP_EOL;
        }

        return $html;
    }


    /**
     * @param  string  $string
     * @return string
     */
    public static function convertSmartQuotes($string) {
        //converts smart quotes to normal quotes.
        $search = array(chr(145), chr(146), chr(147), chr(148), chr(151));
        $replace = array("'", "'", '"', '"', '-');
        return str_replace($search, $replace, $string);
    }


    /**
     * @param  string  $text
     * @return string
     */
    public static function generateUrlSafeSlug($text) {
        $text = self::convertSmartQuotes(strtolower($text));

        $text = str_replace([
            ' ',
            '.',
            '#',
        ], '-', trim(stripslashes($text)));

        $text = str_replace('&',   'and', $text);
        $text = str_replace('---', '-',   $text);
        $text = str_replace('--',  '-',   $text);

        $text = str_replace([
            ',',
            "'",
            '"',
            '!',
            '?',
            '/',
            '\\',
            ':',
            ';',
            '<',
            '>',
            '@',
            '*',
            '(',
            ')',
            '$',
            '%',
            '+',
            '=',
            '~',
            '`',
            '^',
        ], '', $text);

        return $text;
    }


    /**
     * @param  int   $length
     * @return string
     */
    public static function getRandomString($length = 24)
    {
        $string = '';
        mt_srand ((double) microtime() * 1000000);
        for ($i = 1; $i <= $length; $i++) {
            $nRandom = mt_rand(1,30);
            if ($nRandom <= 10) {
                // Uppercase letters
                $string .= chr(mt_rand(65, 90));
            } elseif ($nRandom <= 20) {
                // Numbers
                $string .= mt_rand(0, 9);
            } else {
                // Lowercase letters
                $string .= chr(mt_rand(97, 122));
            }
        }
        return $string;
    }


    /**
     * @param $query
     */
    public static function debugQuery($query)
    {
        print '<pre>';
        print $query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;
        print '</pre>';
        exit();
    }
}
