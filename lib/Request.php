<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class Request {

    static protected $instance = null;

    protected $data = null;
    protected $bodyStream = null;
    protected $body = null;

    static public function variable($name) {
        return self::get()->getVar($name);
    }

    static public function header($name) {
        return self::get()->getHeader($name);
    }

    static public function body() {
        return self::get()->getBody();
    }

    /**
     * This function is for testing purpose. Use this to mock the request.
     * @param $data
     */
    static public function createFromData($data) {
        self::$instance = new $class($data);
    }

    static public function get() {
        if (self::$instance === null) {
            $class = get_called_class();
            $headers = array();
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            self::$instance = new $class(array(
                'post' => count($_POST),
                'variables' => $_REQUEST,
                'headers' => $headers
            ), STDIN);

        }
        return self::$instance;
    }

    public function __construct($data, $bodyStream) {
        $this->data = $data;
        $this->bodyStream = $bodyStream;
    }

    public function getVar($name) {
        return isset($this->data[$name])
            ? $this->data[$name]
            : null;
    }

    public function getBody() {
        if ($this->body === null) {
            $this->body = stream_get_contents($this->bodyStream);
        }
        return $this->body;
    }

    public function getHeader($name = null) {
        if ($name !== null) {
            return isset($this->data['headers'][$name])
                ? $this->data['headers'][$name]
                : null;
        } else {
            return $this->data['headers'];
        }
    }

    public function isPost() {
        return (bool) isset($this->data['post'])
            ? $this->data['post']
            : false;
    }
}