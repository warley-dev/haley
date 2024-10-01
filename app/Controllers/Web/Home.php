<?php

namespace App\Controllers\Web;

class Home
{
    public function index()
    {
        return view('home.index', []);
    }

    public function create()
    {
        return view('home.create', []);
    }

    public function update()
    {
        return view('home.update', []);
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