<?php

/**
 * Class common\components\CurlRequest
 *
 */

namespace common\components;

class CurlRequest {

    /**
     * The Socket Timout limit in seconds
     *
     * @var     int
     * @access  protected
     *
     **/
    protected $_timeout = 30;

    /**
     * The User Agent to send
     *
     * @var     string
     * @access  protected
     *
     **/
    protected $_user_agent = 'RPC Client';

    /**
     * Should the request Fail if page gets a 400 or above status code?
     *
     * @var     bool
     * @access  protected
     *
     **/
    protected $_fail_on_error = true;

    /**
     * Should the request follow "Location" header redirects?
     *
     * @var     bool
     * @access  protected
     *
     **/
    protected $_follow_location_headers = true;

    /**
     * The last connection based error message
     *
     * @var     string
     * @access  protected
     *
     **/
    protected $_last_error = '';

    /**
     * The last connection stats
     *
     * @var     array
     * @access  protected
     *
     **/
    protected $_last_stats = null;


    /**
     * Constructor
     *
     * @access public
     * @return \Curl_Request
     */
    public function __construct() {

    }


    /**
     * getTimeout
     *
     * @access public
     * @return int
     */
    public function getTimeout() {
        return $this->_timeout;
    }


    /**
     * setTimeout
     *
     * @access public
     * @param  int    $timeout
     * @return void
     */
    public function setTimeout($timeout) {
        $this->_timeout = intval($timeout);
    }


    /**
     * getUserAgent
     *
     * @access public
     * @return string
     */
    public function getUserAgent() {
        return $this->_user_agent;
    }


    /**
     * setUserAgent
     *
     * @access public
     * @param  string  $user_agent
     * @return void
     */
    public function setUserAgent($user_agent) {
        $this->_user_agent = $user_agent;
    }


    /**
     * getFailOnError
     *
     * @access public
     * @return bool
     */
    public function getFailOnError() {
        return $this->_fail_on_error;
    }


    /**
     * setFailOnError
     *
     * @access public
     * @param  bool     $bool
     * @return void
     */
    public function setFailOnError($bool) {
        $this->_fail_on_error = (bool) $bool;
    }


    /**
     * getFollowLocationHeaders
     *
     * @access public
     * @return bool
     */
    public function getFollowLocationHeaders() {
        return $this->_follow_location_headers;
    }


    /**
     * setFollowLocationHeaders
     *
     * @access public
     * @param  bool     $bool
     * @return void
     */
    public function setFollowLocationHeaders($bool) {
        $this->_follow_location_headers = (bool) $bool;
    }


    /**
     * getLastError
     *
     * @access public
     * @return string
     */
    public function getLastError() {
        return $this->_last_error;
    }


    /**
     * getLastStats
     *
     * @access public
     * @return array
     */
    public function getLastStats() {
        return $this->_last_stats;
    }


    /**
     * head
     *
     * @access public
     * @param  string  $url
     * @return mixed
     */
    public function head($url) {
        $curl_opt_array = array(
            // Set Url
            CURLOPT_URL            => $url,
            // Return a String
            CURLOPT_RETURNTRANSFER => true,
            // Timeouts
            CURLOPT_CONNECTTIMEOUT => $this->getTimeout(),
            CURLOPT_TIMEOUT        => $this->getTimeout(),
            // UA
            CURLOPT_USERAGENT      => $this->getUserAgent(),
            // Fail for 400+
            CURLOPT_FAILONERROR    => $this->getFailOnError(),
            // Follow "Location" headers
            CURLOPT_FOLLOWLOCATION => $this->getFollowLocationHeaders(),
            // Only do a HEAD request
            CURLOPT_NOBODY         => true,
        );

        return $this->_request($curl_opt_array);
    }


    /**
     * get
     *
     * @access public
     * @param  string  $url
     * @return mixed
     */
    public function get($url) {
        $curl_opt_array = array(
            // Set Url
            CURLOPT_URL            => $url,
            // Return a String
            CURLOPT_RETURNTRANSFER => true,
            // Timeouts
            CURLOPT_CONNECTTIMEOUT => $this->getTimeout(),
            CURLOPT_TIMEOUT        => $this->getTimeout(),
            // UA
            CURLOPT_USERAGENT      => $this->getUserAgent(),
            // Fail for 400+
            CURLOPT_FAILONERROR    => $this->getFailOnError(),
            // Follow "Location" headers
            CURLOPT_FOLLOWLOCATION => $this->getFollowLocationHeaders(),
        );

        return $this->_request($curl_opt_array);
    }


    /**
     * post
     *
     * @access public
     * @param  string  $url
     * @param  array   $post_data_array
     * @return mixed
     */
    public function post($url, $post_data_array) {
        $curl_opt_array = array(
            // Set Url
            CURLOPT_URL            => $url,
            // Return a String
            CURLOPT_RETURNTRANSFER => true,
            // Timeouts
            CURLOPT_CONNECTTIMEOUT => $this->getTimeout(),
            CURLOPT_TIMEOUT        => $this->getTimeout(),
            // UA
            CURLOPT_USERAGENT      => $this->getUserAgent(),
            // Fail for 400+
            CURLOPT_FAILONERROR    => $this->getFailOnError(),
            // Follow "Location" headers
            CURLOPT_FOLLOWLOCATION => $this->getFollowLocationHeaders(),
            // This is POST
            CURLOPT_POST           => true,
            // Post Fields
            CURLOPT_POSTFIELDS     => $post_data_array,
        );

        return $this->_request($curl_opt_array);
    }


    /**
     * delete
     *
     * @access public
     * @param  string        $url
     * @param  string|array  $body_data
     * @return mixed
     */
    public function delete($url, $body_data = '') {
        $curl_opt_array = array(
            // Set Url
            CURLOPT_URL            => $url,
            // Return a String
            CURLOPT_RETURNTRANSFER => true,
            // Timeouts
            CURLOPT_CONNECTTIMEOUT => $this->getTimeout(),
            CURLOPT_TIMEOUT        => $this->getTimeout(),
            // UA
            CURLOPT_USERAGENT      => $this->getUserAgent(),
            // Fail for 400+
            CURLOPT_FAILONERROR    => $this->getFailOnError(),
            // Follow "Location" headers
            CURLOPT_FOLLOWLOCATION => $this->getFollowLocationHeaders(),
            // This is DELETE
            CURLOPT_HEADER         => 0,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            // Post Fields
            CURLOPT_POSTFIELDS     => $body_data,
        );

        return $this->_request($curl_opt_array);
    }


    /**
     * _request
     *
     * @access protected
     * @param  array   $curl_opt_array
     * @return mixed
     */
    protected function _request($curl_opt_array) {
        $this->_last_error = '';
        $this->_last_stats = null;

        $resource = curl_init();
        curl_setopt_array($resource, $curl_opt_array);

        // Send!
        $response = curl_exec($resource);

        // Stats
        $this->_last_stats = curl_getinfo($resource);

        // Errors and redirect failures
        if (!$response && !$this->getFollowLocationHeaders() && !curl_errno($resource)) {
            $response = null;
        } else if (!$response) {
            $response = false;
            $this->_last_error =  curl_errno($resource).' - '.curl_error($resource);
        }

        curl_close($resource);
        return $response;
    }

}


