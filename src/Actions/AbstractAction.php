<?php

namespace TaskForce\Actions;

abstract class AbstractAction
{
    abstract public static function getTitle(): string;

    abstract public static function getName(): string;

    abstract public static function isAllowed(bool $isOwner, string $status, string $role): bool;
}
