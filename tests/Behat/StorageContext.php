<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

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

    public function retrieve($input)
    {
        if ($input instanceof PyStringNode) {
            return $this->replaceForPyNodeString($input);
        }

        if ($input instanceof TableNode) {
            return $this->replaceForTable($input);
        }

        return $this->retrieveKey($input);
    }

    private function replaceForPyNodeString(PyStringNode $string): PyStringNode
    {
        $returned = preg_replace_callback("/\{\{((\w+)\.?(.*?))\}\}/", function ($matches) {
            return $this->retrieveKey($matches[0]);
        }, $string->getRaw());

        if ($returned === null) {
            return $string;
        }
        return new PyStringNode([$returned], 1);
    }

    private function replaceForTable(TableNode $table): TableNode
    {
        $processedTable = [];
        foreach ($table->getColumnsHash() as $row) {
            $value = $this->retrieveKey($row['value']);
            $value = is_object($value) ? $value->__toString() : $value;
            $processedTable[] = [$row['key'], $value];
        }
        return new TableNode(array_merge([['key', 'value']], $processedTable));
    }

    private function retrieveKey(string $key)
    {
        $key = str_replace(['{{', '}}'], '', $key);
        [$objectWanted, $property] = explode('.', $key);
        $value = $this->data[$objectWanted];

        if (!$value) {
            return $key;
        }

        if (!is_object($value)) {
            return $value;
        }

        $object = $value;

        if ($property) {
            $functions = ['get', 'is'];
            foreach ($functions as $function) {
                if (method_exists($object, $function . $property)) {
                    return call_user_func([$object, $function . $property]);
                }
            }
        }

        return $object;
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
