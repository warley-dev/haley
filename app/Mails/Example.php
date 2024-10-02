<?php

namespace App\Mails;

use Haley\Collections\Mailer;

class Example extends Mailer
{
    public function make(mixed $data = null)
    {
        $this->subject('Example title');

        $this->recipient('example@hotmail.com', 'Example');

        $this->content('Helo word');

        return $this;
    }
}