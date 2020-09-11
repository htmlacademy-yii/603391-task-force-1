<?php


namespace frontend\models\forms;


use frontend\models\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoriesFilterForm extends Model
{
    private $categories;
    private $categoriesId;

    public function init()
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


}
