<?php


namespace App\Controllers\Web{{ $namespace ? "\\$namespace" : '' }};

class {{$name}}

{
    public function index()
    {
        return view('{{strtolower($name)}}.index', []);
    }

    public function create()
    {
        return view('{{strtolower($name)}}.create', []);
    }

    public function update()
    {
        return view('{{strtolower($name)}}.update', []);
    }

    public function formCreate()
    {
        // ...
    }

    public function formUpdate()
    {
        // ...
    }

    public function formDelete()
    {
        // ...
    }

    public function search()
    {
        // ...
    }
}
