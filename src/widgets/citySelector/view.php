<div class="header__town">
    <label>
        <select class="multiple-select input town-select" size="1" name="town[]">
            <?php
            /** @var array $cities */
            /** @var int $currentCityId */

            foreach ($cities as $key => $city):?>
                <option  <?=($key === $currentCityId) ? 'selected' : '' ?>  value="<?= $key ?>"><?= $city ?>
                </option>
            <?php
            endforeach; ?>
        </select>
    </label>
</div>