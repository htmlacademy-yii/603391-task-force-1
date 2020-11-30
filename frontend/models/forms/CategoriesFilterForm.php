<?php

namespace frontend\models\forms;

use frontend\models\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-write int $oneCategory
 * @property-read array $categoriesState
 */
class CategoriesFilterForm extends Model
{
    private ?array $categories = null;
    private ?array $categoriesId = null;

    public function init(): void
    {
        $this->categoriesId = ArrayHelper::map(Category::find()->select(['id', 'name'])->all(), 'id', 'name');

        foreach ($this->categoriesId as $key => $element) {
            $this->categories[$key] = true;
        }
    }

    public function __get($name): ?bool
    {
        if (array_key_exists($name, $this->categories)) {
            return $this->categories[$name];
        }

        return null;
    }

    public function getCategoriesState(): array
    {
        return $this->categories;
    }

    public function __set($name, $value): void
    {
        if (array_key_exists($name, $this->categories)) {
            $this->categories[$name] = $value;
        }
    }

    public function attributeLabels(): ?array
    {
        return $this->categoriesId;
    }

    public function updateProperties(array $values): void
    {
        foreach ($values as $name => $value) {
            if (array_key_exists($name, $this->categories)) {
                $this->categories[$name] = $value;
            }
        }
    }

    public function setOneCategory(int $id): void
    {
        foreach ($this->categoriesId as $key => $element) {
            $this->categories[$key] = false;
        }
        $this->categories[$id] = true;
    }
}
