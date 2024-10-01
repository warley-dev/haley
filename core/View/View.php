<?php

namespace Haley\View;

use Haley\View\Engine\FileEngine;


class View
{
    public function view(string $view, array|object $params = [], bool $render = true, string|null $path = null)
    {
        if (ob_get_level() > 0) ob_clean();

        $engine = new FileEngine;

        if ($path === null) $path = directoryRoot('resources/views/');

        $file = $path . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.view.php';

        $view = $engine->getView($file);

        if (!$view) return '';

        if ($render) {
            foreach ($params as $key => $value) $$key = $value;

            require $view;
        } else {
            foreach ($params as $key => $value) $$key = $value;

            ob_start();

            require $view;

            return ob_get_clean();
        }
    }
}
