<?php

namespace App\Service\Welcome;

class Welcome implements WelcomeInterface
{

    public function hello(string $message = null): string
    {
        return "Bienvenue chez Dawan";
    }
}
