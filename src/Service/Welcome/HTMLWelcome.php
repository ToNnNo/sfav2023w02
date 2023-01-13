<?php

namespace App\Service\Welcome;

class HTMLWelcome implements WelcomeInterface
{

    public function hello(): string
    {
        return "<strong>Bienvenue chez Dawan</strong>";
    }
}
