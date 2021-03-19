<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;

class StorageContext implements Context
{
    private array $data = [];

    public function getKeys(): array
    {
        return array_keys($this->data);
    }

    public function exists(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function retrieve(string $key)
    {
        return $this->data[$key];
    }

    public function store(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    public function unset(string $key)
    {
        unset($this->data[$key]);
    }
}
