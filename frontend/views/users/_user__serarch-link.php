            <div class="user__search-link">
                <p>Сортировать по:</p>
                <ul class="user__search-list">
                    <?php
                    /** @var string $sortType */

                    use TaskForce\SortingUsers;
                    use yii\helpers\Url;

                    foreach (SortingUsers::SORTS as $sort): ?>
                        <li class="user__search-item <?=
                        ($sortType == $sort) ? ' user__search-item--current' : '' ?>">
                            <a href="<?= URL::to(['users/index', 'sortType' => $sort]) ?>"
                               class="link-regular"><?= $sort ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>