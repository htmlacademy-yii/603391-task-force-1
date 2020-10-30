<?php


namespace TaskForce;


class SortingUsers
{
    public const SORT_BY_RATING = 'Рейтингу';
    public const SORT_BY_COUNT_TASK = 'Числу заказов';
    public const SORT_BY_POPULARITY = 'Популярности';

    public const SORTS = [self::SORT_BY_RATING, self::SORT_BY_COUNT_TASK, self::SORT_BY_POPULARITY];

}
