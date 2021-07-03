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
    const ENABLE_VALUE = '1';
    const DISABLE_VALUE = '0';
    public ?array $categories = null;
    public ?array $categoriesId = null;

    /**
     * @inheritdoc
     */
    public function init(string $defaultValue = self::DISABLE_VALUE)
    {
        $this->categoriesId = ArrayHelper::map(Category::find()->select(['id', 'name'])->all(), 'id', 'name');

        foreach ($this->categoriesId as $key => $element) {
            $this->categories[$key] = $defaultValue;
        }
    }

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        if ($formName) {
            $this->categories = $data[$formName]['categories'];
            return true;
        }

        return false;
    }

    /**
     *  @inheritdoc
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->categories)) {
            return $this->categories[$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getCategoriesState(): array
    {
        return $this->categories;
    }

    /**
     *  @inheritdoc
     */
    public function __set($name, $value)
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
        $profile = Profile::findByUserId($userId);

        if (!$profile) {
            throw new TaskForceException('Профиль пользователя не найден.');
        }

        $this->categoriesId = ArrayHelper::map(Category::find()->select(['id', 'name'])->all(), 'id', 'name');
        $spec = Specialization::find()->select(['category_id'])
            ->where(['profile_id' => $profile['profile_id']])->asArray()
            ->all();
        $this->init();
        foreach ($spec as $element) {
            $this->categories[$element['category_id']] = self::ENABLE_VALUE;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
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

}
