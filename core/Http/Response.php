<?php

namespace Haley\Http;

use Haley\Collections\HttpCodes;
use Haley\Collections\MimeTypes;
use Haley\Kernel;
use Throwable;

class Response
{
    public static function header(string $name, string $value)
    {
        try {
            header("$name: $value");

            return true;
        } catch (Throwable) {
        }

        return false;
    }

    public static function status(int $status)
    {
        return http_response_code($status);
    }

    public static function abort(int $status = 404, string|null $mesage = null)
    {
        if (ob_get_level() > 0) ob_clean();

        response()->status($status);

        if ($mesage === null) $mesage = HttpCodes::get($status);

        if (file_exists(directoryRoot('resources/views/error/' . $status . '.view.php'))) {
            view('error.' . $status, [
                'status' => $status,
                'mesage' => $mesage
            ]);
        } else if (file_exists(directoryRoot('resources/views/error/default.view.php'))) {
            view('error.default', [
                'status' => $status,
                'mesage' => $mesage
            ]);
        }

        Kernel::terminate();
    }

    public static function json(mixed $value, int|null $status = null)
    {
        if (ob_get_level() > 0) ob_clean();

        if ($status !== null) self::status($status);

        self::header('Content-type', 'application/json; charset=utf-8');

        print(json_encode($value));

        Kernel::terminate();
    }

    public static function download(string $file, string $rename = null, int|null $status = null)
    {
        if (file_exists($file)) {
            if (ob_get_level() > 0) ob_clean();

            if ($rename == null) {
                $file_name = basename($file);
            } else {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $file_name = $rename . '.' . $extension;
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));

            if ($status !== null) self::status($status);

            readfile($file);

            return;
        }

        return response()->abort(404);
    }

    public static function file(string $file, int|null $status = null)
    {
        if (file_exists($file)) {
            if (ob_get_level() > 0) ob_clean();

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            self::header('Content-type', MimeTypes::get($extension));
            self::header('Content-Length', filesize($file));

            if ($status !== null) self::status($status);

            readfile($file);

            return;
        }

        return response()->abort(404);
    }
}
