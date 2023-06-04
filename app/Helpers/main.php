<?php
// funcoes helpers disponiveis globalmente

/**
 * Global function example
 */
function example()
{
    return 'helo word';
}

/**
 * Verifica se a url atual é a mesma que a url passada.
 * Deve ser passado a url completa.
 * @return true|false
 */
function urlActive(string $path)
{   
    if (env('APP_URL') . $path == request()->url($path)) {
        return true;
    } else {
        return false;
    }
}