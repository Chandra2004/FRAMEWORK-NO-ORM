<?php

namespace TheFramework\Console;

interface CommandInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function run(array $args): void;
}
