<?php

namespace Qafoo\Page;

use Qafoo\Page;

class Login extends Page
{
    const PATH = '/login';

    public function setUser($user)
    {
        $this->find('#username')->setValue($user);
    }

    public function setPassword($password)
    {
        $this->find('#password')->setValue($password);
    }

    public function login()
    {
        $this->find('#submit')->press();

        return $this->createFromDocument();
    }
}
