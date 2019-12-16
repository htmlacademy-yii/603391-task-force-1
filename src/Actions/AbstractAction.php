<?php
namespace TaskForce\Actions;

abstract class AbstractAction
{
    abstract public static function getTitle() : string;
    abstract public static function getName() : string;
    abstract public static function isAllowed(string $role, string $status) : bool;
}
