<?php

namespace Haley\Exceptions;

use Haley\Collections\Log;
use Error;
use ErrorException;
use Exception;
use InvalidArgumentException;
use PDOException;
use Throwable;
use TypeError;
use UnderflowException;

class Exceptions
{
    public function handler(callable $debug)
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        });

        try {
            return call_user_func($debug);
        } catch (PDOException $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        } catch (Throwable $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        } catch (Error $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        } catch (UnderflowException $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        } catch (InvalidArgumentException $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        } catch (Exception $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        } catch (TypeError $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        } catch (ErrorException $error) {
            Log::create('framework', '[' . $error->getFile() . ':' . $error->getLine() . '] ' . $error->getMessage());
            return (new Debug)->exceptions($error);
        }
    }
}
