<?php

/**
 * Class common\components\Util
 *
 */

namespace common\components;

class Url
{

    /**
     * Stores an array of the GET vars for the url
     *
     * @var     object
     * @access  protected
     *
     **/
    protected $vars = array();

    /**
     * Stores the build URI string
     *
     * @var     object
     * @access  protected
     *
     **/
    protected $uriString = '';

    /**
     * Stores the base url
     *
     * @var     object
     * @access  protected
     *
     **/
    protected $baseUrl = '';

    /**
     * Stores the url fragment, only works for absolute url, http://host/query#fragment
     *
     * @var     object
     * @access  protected
     *
     **/
    protected $urlFragment = '';


    /**
     * Constructor
     *
     * @access public
     * @param  string $url
     */
    public function __construct($url = null)
    {
        if ($url === false) {
            $url = $_SERVER['REQUEST_URI'];
        }

        list($this->baseUrl, $queryString) = explode('?', $url, 2);

        // This function produces warnings when it doesn't like the URL format.
        // @lets shut it up

        if (strstr($url, '://')) {
            $urlParts = parse_url($url);
            parse_str($urlParts['query'], $this->vars);

            // :NOTE: check that the base url doesnt already contain the fragment, otherwise we may be adding it twice:
            if (isset($urlParts['fragment']) && !strstr($this->baseUrl, $urlParts['fragment'])) {
                $this->urlFragment = $urlParts['fragment'];
            } else {
                $this->urlFragment = '';
            }
        } else {
            parse_str($queryString, $this->vars);
            $this->urlFragment = '';
        }
    }


    /**
     * setVar
     * Sets a GET Var for the URI
     *
     * @access public
     * @param  string  $varName
     * @param  string  $value
     * @return void
     */
    public function setVar($varName, $value)
    {
        if (is_array($varName)) {
            if (!isset($this->vars[$varName[0]])) {
                $this->vars[$varName[0]] = array();
            }

            $this->vars[$varName[0]][$varName[1]] = $value;
        } else {
            $this->vars[$varName] = $value;
        }
    }


    /**
     * getVar
     * Attempts to return a GET var, if found.
     *
     * @access public
     * @param  string  $varName
     * @return string/bool
     */
    public function getVar($varName)
    {
        if (is_array($varName)) {
            if (isset($this->vars[$varName[0]]) && isset($this->vars[$varName[0]][$varName[1]])) {
                return $this->vars[$varName[0]][$varName[1]];
            } else {
                return false;
            }
        } else {
            return $this->vars[$varName];
        }
    }


    /**
     * delVar
     * Removed a Get var from the URI
     *
     * @access public
     * @param  string  $varName
     * @return void
     */
    public function delVar($varName)
    {
        if (is_array($varName)) {
            if (isset($this->vars[$varName[0]]) && isset($this->vars[$varName[0]][$varName[1]])) {
                unset($this->vars[$varName[0]][$varName[1]]);
            }
        } else {
            unset($this->vars[$varName]);
        }
    }


    /**
     * getUri
     * Builds the URI component
     *
     * @access public
     * @return string
     */
    public function getUri()
    {
        $varCount = 0;

        reset($this->vars);
        while (list($var, $value) = each($this->vars)) {
            if (!$varCount++) {
                $this->uriString = '?';
            } else {
                $this->uriString .= '&';
            }

            if (is_array($value)) {
                while (list($index, $arrValue) = each($value)) {
                    if ((count($value) > 1) && ($varCount >= 1)) {
                        $this->uriString .= '&';
                    }

                    $this->uriString .= $var . '[' . $index . ']=' . urlencode($arrValue);
                }
            } else {
                $this->uriString .= $var . '=' . urlencode($value);
            }
        }

        if ($this->urlFragment) {
            $this->uriString .= '#'.$this->urlFragment;
        }

        return $this->uriString;
    }


    /**
     * getUrl
     * Gets the built URL
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->baseUrl . $this->getUri();
    }


    /**
     * get
     * shortcut for getUrl
     *
     * @access public
     * @return string
     */
    public function get()
    {
        return $this->getUrl();
    }
}

