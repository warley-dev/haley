<?php

namespace Haley\Validator;

/**
 * MCQUERY VALIDATOR
 */
class Validator
{
    private array $inputs = [];
    private array $request = [];
    private array $errors = [];
    private string $mold_start = '';
    private string $mold_end = '';

    public function __construct(false|null|array $request = null)
    {
        if (empty($request)) $request = [];

        $this->request = $request;
    }

    /**
     * Requeridos
     */
    public function required(string|array $input, string $mesage = 'Requerido')
    {
        if (is_string($input)) {
            $input = [$input];
        }

        if (is_array($input)) {
            foreach ($input as $value) {
                if (isset($this->request[$value])) {
                    if (is_array($this->request[$value])) {
                        if (count($this->request[$value]) == 0) {
                            $this->errors[$value][] = $this->e_mold($mesage);
                        }
                    } else {
                        if ($this->request[$value] == '' or $this->request[$value] == false or $this->request[$value] == null) {
                            $this->errors[$value][] = $this->e_mold($mesage);
                        }
                    }
                    $this->inputs[$value] = $this->request[$value];
                } else {
                    $this->errors[$value][] = $this->e_mold($mesage);
                }
            }

            return;
        }
    }

    /**
     * Equivalente a str_replace   
     */
    public function replace(string $input, array|string $search, string|array $new = '', int $count = null)
    {
        if (isset($this->request[$input])) {
            $this->request[$input] = str_replace($search, $new, $this->request[$input], $count);
            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Tipos de arquivos validos png, jpg, etc..
     */
    public function file_extension(string $input, array $extensions, string $mesage = 'Formatos validos X')
    {
        $files = request()->file($input);
        if ($files) {
            $valid = true;
            if (is_array($files['name'])) {
                foreach ($files['name'] as $file) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    if (!in_array($extension, $extensions)) {
                        $valid = false;
                    }
                }
            } else {
                $extension = pathinfo($files['name'], PATHINFO_EXTENSION);
                if (!in_array($extension, $extensions)) {
                    $valid = false;
                }
            }

            if ($valid == false) {
                if ($mesage == 'Formatos validos X') {
                    $all_formats = '';
                    foreach ($extensions as $value) {
                        $all_formats =  $all_formats . $value . ', ';
                    }

                    $mesage = 'Formatos validos ( ' . rtrim($all_formats, ', ') . ' )';
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $files;
        }

        return;
    }

    /**
     * Tamanho maximo do arquivo, caso seja multiplos arquivos o valor sera somado (1 MB = 1048576)
     */
    public function file_size(string $input, int $bytes, string $mesage = 'Tamanho maximo X')
    {
        $files = request()->file($input);
        if ($files) {
            $size = 0;

            if (is_array($files['size'])) {
                foreach ($files['size'] as $value) {
                    $size += $value;
                }
            } else {
                $size = $files['size'];
            }

            if ($size > $bytes) {
                if ($mesage == 'Tamanho maximo X') {
                    $mesage = 'Tamanho maximo ' . formatSize($bytes);
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $files;
        }

        return;
    }

    /**
     * Quantidade maxima de arquivos
     */
    public function file_max(string $input, int $max, string $mesage = 'Maximo x arquivos')
    {
        $files = request()->file($input);
        if ($files) {
            $max_files = (int)1;

            if (is_array($files['name'])) {
                $max_files = count($files['name']);
            }

            if ($max_files > $max) {
                if ($mesage == 'Maximo x arquivos') {
                    $mesage = 'Maximo ' . $max . ' arquivos';
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $files;
        }

        return;
    }

    /**
     * Equivalente a number_format  
     */
    function number_format(string $input, int $decimals = 2, string $decimal_separator = '.', string $thousands_separator = '')
    {
        if (isset($this->request[$input])) {
            $this->request[$input] = number_format((float)$this->request[$input], $decimals, $decimal_separator, $thousands_separator);
            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Minimo de caracteres
     */
    public function min(string $input, int $min, string $mesage = 'Minimo x caracteres')
    {
        if (isset($this->request[$input])) {
            if (strlen($this->request[$input]) < $min) {
                if ($mesage == 'Minimo x caracteres') {
                    $mesage = 'Minimo ' . $min . ' caracteres';
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }
    }

    /**
     * Maximo de caracteres
     */
    public function max(string $input, int $max, string $mesage = 'Maximo x caracteres')
    {
        if (isset($this->request[$input])) {
            if (strlen($this->request[$input]) > $max) {
                if ($mesage == 'Maximo x caracteres') {
                    $mesage = 'Maximo ' . $max . ' caracteres';
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }
    }

    /**
     * Quantidade especifica de caracteres
     */
    public function size(string $input, int $size, string $mesage = 'x caracteres necessários')
    {
        if (isset($this->request[$input])) {
            if (strlen((string)$this->request[$input]) != $size) {
                if ($mesage == 'x caracteres necessários') {
                    $mesage =  $size . ' caracteres necessários';
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }
    }

    /**
     * Valor minimo de um número
     */
    public function min_value(string $input, int|float $min, string $mesage = 'Minimo x')
    {
        if (isset($this->request[$input])) {
            if ($this->request[$input] < $min and is_numeric($min) and strlen($this->request[$input]) > 0) {
                if ($mesage == 'Minimo x') {
                    $mesage = 'Minimo ' . $min;
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Valor maximo de um número
     */
    public function max_value(string $input, int|float $max, string $mesage = 'Maximo x')
    {
        if (isset($this->request[$input])) {
            if ($this->request[$input] > $max and is_numeric($max) and strlen($this->request[$input]) > 0) {
                if ($mesage == 'Maximo x') {
                    $mesage = 'Maximo ' . $max;
                }

                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Tipo email
     */
    public function email(string $input, string $mesage = 'E-mail inválido')
    {
        if (isset($this->request[$input])) {
            if (!filter_var($this->request[$input], FILTER_VALIDATE_EMAIL) and strlen($this->request[$input]) > 0) {
                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Tipo url
     */
    public function url(string $input, string $mesage = 'URL inválido')
    {
        if (isset($this->request[$input])) {
            if (!filter_var($this->request[$input], FILTER_VALIDATE_URL) and strlen($this->request[$input]) > 0) {
                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Apenas números
     */
    public function numeric(string $input, string $mesage = 'Apenas números')
    {
        if (isset($this->request[$input])) {
            if (!is_numeric($this->request[$input]) and strlen($this->request[$input]) > 0) {
                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Apenas letras
     */
    public function letters(string $input, string $mesage = 'Apenas letras')
    {
        if (isset($this->request[$input])) {
            if (strlen($this->request[$input]) > 0 and is_numeric(filter_var($this->request[$input], FILTER_SANITIZE_NUMBER_INT))) {
                $this->errors[$input][] = $this->e_mold($mesage);
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Tipo inteito
     */
    public function int(string $input, string $mesage = 'Apenas números inteiros')
    {
        if (isset($this->request[$input])) {
            if (strlen($this->request[$input]) > 0) {
                if ((int)$this->request[$input] != $this->request[$input] or !is_numeric($this->request[$input])) {
                    $this->errors[$input][] = $this->e_mold($mesage);
                }
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Tipo float
     */
    public function float(string $input, string $mesage = 'Apenas números float')
    {
        if (isset($this->request[$input])) {
            if (strlen($this->request[$input]) > 0) {
                if (!is_float((float)$this->request[$input]) or !is_numeric($this->request[$input]) or strpos((string)$this->request[$input], '.') === false) {
                    $this->errors[$input][] = $this->e_mold($mesage);
                }
            }

            $this->inputs[$input] = $this->request[$input];
        }

        return;
    }

    /**
     * Verifica o formato dos números sendo x os números, o resto será removido
     * @param string $format exemplo (xx) xxxxx-xxxx 
     */
    public function mask(string $input, string $format, string $mesage = 'Formato inválido x')
    {
        if (isset($this->request[$input]) and strlen($this->request[$input]) > 0) {
            $array_input = str_split($this->request[$input]);
            $array_format = str_split($format);

            $new_input = '';
            $checker = true;
            $return = '';

            foreach ($array_input as $key => $value) {
                if (is_numeric($value)) {
                    $new_input .= 'x';
                } else {
                    $new_input .= $value;
                }

                if (isset($array_format[$key])) {
                    if ($array_format[$key] == 'x') {
                        if (!is_numeric($value)) {
                            $checker = false;
                        } else {
                            $return .= $value;
                        }
                    }
                } else {
                    $checker = false;
                }
            }

            if ($new_input != $format or $checker == false) {
                if ($mesage == 'Formato inválido x') {
                    $mesage = 'Formato inválido ' . $format;
                }

                $this->inputs[$input] = false;
                $this->errors[$input][] = $this->e_mold($mesage);
            } else {
                $this->inputs[$input] = (int)$return;
            }
        }

        return;
    }

    /**
     * Faz o registro de erros para serem usados nos view
     */
    public function register()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $page = $_SERVER['HTTP_REFERER'];

            if (count($this->errors) > 0) {
                $_SESSION['VALIDATOR'][$page]['ERRORS'] = $this->errors;
                $_SESSION['VALIDATOR'][$page]['INPUTS'] = $this->inputs;
            } elseif (isset($_SESSION['VALIDATOR'][$page]['ERRORS'])) {
                $_SESSION['VALIDATOR'][$page]['ERRORS'] = false;
            }
        }

        return;
    }

    /**
     * Coloca as mensagens de erro entre $start e $end
     */
    public function mold(string $start, string $end)
    {
        $this->mold_start = $start;
        $this->mold_end = $end;
        return;
    }

    private function e_mold(string $input)
    {
        return $this->mold_start . $input . $this->mold_end;
    }

    /**
     * Retorna os valores dos inputs filtrados
     * @return array|string|false 
     */
    public function get(string $input = null)
    {
        if ($input != null) {
            if (isset($this->inputs[$input])) {
                return $this->inputs[$input];
            }
        }

        if (count($this->inputs) > 0) {
            return $this->inputs;
        }

        return false;
    }

    /**
     * Retorna todos os erros ou false se não existir erros
     * @param strign $input especificar qual item.
     * @return array|string|false
     */
    public function errors(string $input = null)
    {
        if (count($this->errors) > 0) {
            if ($input != null) {
                if (isset($this->errors[$input])) {
                    return $this->errors[$input];
                } else {
                    return false;
                }
            }

            return $this->errors;
        }

        return false;
    }

    /**
     * Cria uma mensagem de erro para o input
     */
    public function create_error(string $input, string $mesage)
    {
        $this->errors[$input][] = $this->e_mold($mesage);
    }

    /**
     * Retorna o primeiro erro encontrado
     * @param strign $input especificar qual item.
     * @return array|string|false
     */
    public function error_fist(string $input = null)
    {
        if (count($this->errors) > 0) {
            if ($input == null) {
                foreach ($this->errors as $fist) {
                    return $fist[0];
                }
            } else {
                if (isset($this->errors[$input])) {
                    return $this->errors[$input][0];
                } else {
                    return false;
                }
            }
        }

        return false;
    }
}