<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;

class DateContext implements Context
{
    /**
     * @param PyStringNode|string $input
     * @return PyStringNode|string
     */
    public function replaceDates($input)
    {
        if ($input instanceof PyStringNode) {
            $result = $this->replaceFromString($input->getRaw());
            return new PyStringNode([$result], 1);
        }
        return $this->replaceFromString($input);
    }

    private function replaceFromString(string $body): string
    {
        return preg_replace_callback(
            '/\[\[(.*)\]\]/',
            function ($matches) {
                return (new \DateTimeImmutable($matches[1]))->format('c');
            },
            $body
        );
    }
}
