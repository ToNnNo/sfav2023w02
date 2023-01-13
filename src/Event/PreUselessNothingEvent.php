<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PreUselessNothingEvent extends Event
{
    public const PRE_UPDATE_VALUE = "useless.nothing.pre_update_value";

    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): PreUselessNothingEvent
    {
        $this->value = $value;
        return $this;
    }


}
