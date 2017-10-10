<?php

namespace Papertowel\Core;

class Session
{
    const LANGUAGE = 'lang';

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * @param $key string
     * @return string|null
     */
    public function get(string $key) : ?string
    {
        return isset($_SESSION[$key]) === true ? $_SESSION[$key] : null;
    }

    /**
     * @param $key string
     * @param $value string
     */
    public function set(string $key, string $value)
    {
        $_SESSION[$key] = $value;
    }
}