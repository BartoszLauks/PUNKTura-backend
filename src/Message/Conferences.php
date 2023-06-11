<?php

declare(strict_types=1);

namespace App\Message;

class Conferences
{
    private array $conferences;

    public function __construct(
        array $conferences
    ) {
        $this->conferences = $conferences;
    }

    public function getConferences() : array
    {
        return $this->conferences;
    }
}