<?php


namespace App\Mails{{ $namespace ? "\\$namespace" : '' }};

use Haley\Collections\Mailer;

class {{$name}} extends Mailer
{
    public function make(mixed $data = null)
    {
        $this->subject('Example title');

        $this->recipient('example@hotmail.com', 'Example');

        $this->content('Helo word');

        return $this;
    }
}