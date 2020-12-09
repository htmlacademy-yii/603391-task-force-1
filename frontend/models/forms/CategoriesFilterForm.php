<?php

namespace frontend\models\forms;

use frontend\models\Category;
use frontend\models\Specialization;
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

    public function init(bool $defaultValue = true): void
    {
        $this->categoriesId = ArrayHelper::map(Category::find()->select(['id', 'name'])->all(), 'id', 'name');

        foreach ($this->categoriesId as $key => $element) {
            $this->categories[$key] = $defaultValue;
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

    public function loadSpec(): void
    {
        $profileId = (\Yii::$app->user->identity->getProfiles()->asArray()->one()['id']);
        $this->categoriesId = ArrayHelper::map(Category::find()->select(['id', 'name'])->all(), 'id', 'name');
        $spec = Specialization::find()->select(['category_id'])
                                     ->where(['profile_id'=>$profileId])->asArray()
                                     ->all();
        $this->init(false);
        foreach ($spec as $element) {
            $this->categories[$element['category_id']] = true;
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
