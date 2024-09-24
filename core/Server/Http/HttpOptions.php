<?php

namespace Haley\Server\Http;

use Haley\Server\ServerMemory;

class HttpOptions
{
    /**
     * Allow customers to send
     */
    public function receive(bool $value = true)
    {
        $key = array_key_last(ServerMemory::$servers['htttp']);

        ServerMemory::$servers['htttp'][$key]['receive'] = $value;

        return $this;
    }

    /**
     * Set number of connections
     */
    public function connections(int|null $value = null)
    {
        $key = array_key_last(ServerMemory::$servers['htttp']);

        ServerMemory::$servers['htttp'][$key]['connections'] = $value;

        return $this;
    }

    /**
     * Set server name
     */
    public function name(string $value)
    {
        $key = array_key_last(ServerMemory::$servers['htttp']);

        if (empty(ServerMemory::$servers['htttp'][$key]['name'])) {
            ServerMemory::$servers['htttp'][$key]['name'] = $value;
        } else {
            ServerMemory::$servers['htttp'][$key]['name'] .= '.' . $value;
        }

        return $this;
    }

    public function path(string $value)
    {
        $key = array_key_last(ServerMemory::$servers['htttp']);

        ServerMemory::$servers['htttp'][$key]['path'] = $value;

        return $this;
    }

    public function host(string $value)
    {
        $key = array_key_last(ServerMemory::$servers['htttp']);

        ServerMemory::$servers['htttp'][$key]['host'] = $value;

        return $this;
    }

    public function alias(string $value)
    {
        $key = array_key_last(ServerMemory::$servers['htttp']);

        ServerMemory::$servers['htttp'][$key]['alias'] = $value;

        return $this;
    }
}
