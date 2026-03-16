<?php

trait Zend_Legacy_LegacyDynamicProperties
{
    private array $legacyDynamicProps = [];

    public function __set(string $name, $value): void
    {
        $this->legacyDynamicProps[$name] = $value;
    }

    #[\ReturnTypeWillChange]
    public function __get(string $name)
    {
        return $this->legacyDynamicProps[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->legacyDynamicProps[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->legacyDynamicProps[$name]);
    }
}