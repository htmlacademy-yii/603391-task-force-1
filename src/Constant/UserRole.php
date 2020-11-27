<?php

namespace TaskForce\Constant;

class UserRole
{
    public const CUSTOMER = 'customer';
    public const EXECUTOR = 'executor';
    public const LIST = [self::CUSTOMER, self::EXECUTOR];
}
