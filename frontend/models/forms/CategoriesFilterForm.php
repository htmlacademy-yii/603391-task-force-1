<?php

namespace frontend\models\forms;

use frontend\models\Category;
use frontend\models\Profile;
use frontend\models\Specialization;
use TaskForce\Exception\TaskForceException;
use Yii;
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

    public function init(string $defaultValue = '0'): void
    {
        $this->categoriesId = ArrayHelper::map(Category::find()->select(['id', 'name'])->all(), 'id', 'name');

        foreach ($this->categoriesId as $key => $element) {
            $this->categories[$key] = $defaultValue;
        }
    }

    public function load($data, $formName = null)
    {
        if ($formName) {
            $this->categories = $data[$formName]['categories'];
            return true;
        }

        return false;
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

    /**
     * @throws TaskForceException
     */
    public function loadSpec(): void
    {
        $userId = Yii::$app->user->identity->id;
        $profileId = Profile::findByUserId($userId);

        if (!$profileId) {
            throw new TaskForceException('Профиль пользователя не найден.');
        }

        $this->categoriesId = ArrayHelper::map(Category::find()->select(['id', 'name'])->all(), 'id', 'name');
        $spec = Specialization::find()->select(['category_id'])
            ->where(['profile_id' => $profileId])->asArray()
            ->all();
        $this->init();
        foreach ($spec as $element) {
            $this->categories[$element['category_id']] = '1';
        }
    }

    /**
     * @return array|null
     */
    public function attributeLabels(): ?array
    {
        return $this->categoriesId;
    }

    /**
     * @param array $values
     */
    public function updateProperties(array $values): void
    {
        foreach ($values as $name => $value) {
            if (array_key_exists($name, $this->categories)) {
                $this->categories[$name] = $value;
            }
        }
    }

    /**
     * @param int $id
     */
    public function setOneCategory(int $id): void
    {
        foreach ($this->categoriesId as $key => $element) {
            $this->categories[$key] = false;
        }
        $this->categories[$id] = true;
    }

    public function saveData(): void
    {
        $profileId = Profile::findByUserId(yii::$app->user->id)['profile_id'];
        Specialization::deleteAll('profile_id = :profileId', [':profileId' => (int)$profileId]);
        foreach ($this->categories as $name => $value) {
            if (!$value) {
                continue;
            }
            $specialization = new Specialization();
            $specialization->profile_id = $profileId;
            $specialization->category_id = $name;
            $specialization->save();
        }
    }
}
