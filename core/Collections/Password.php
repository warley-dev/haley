<?php

namespace Haley\Collections;

class Password
{
    /**
     * Retorna um hash de uma string
     * @param string $password
     * @return string|false
     */
    public static function create(string $password)
    {
        $rash = password_hash($password, PASSWORD_DEFAULT);
        return $rash;
    }

    /**
     * Verifica se o password bate com o hash, retorna true ou false
     * @param string $password
     * @param string $hash
     * @return true|false
     */
    public static function check(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }
}
