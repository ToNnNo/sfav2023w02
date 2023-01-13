<?php

namespace App\Message;

final class SimpleHelloWorld
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     private $data;

     public function __construct(string $data)
     {
         $this->data = $data;
     }

    public function getData(): string
    {
        return $this->data;
    }
}
