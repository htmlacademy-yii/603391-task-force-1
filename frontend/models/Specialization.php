<?php

namespace frontend\models;

use frontend\models\forms\CategoriesFilterForm;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "specialization".
 *
 * @property int $profile_id
 * @property int $category_id
 * @property Category $category
 */
class Specialization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'specialization';
    }

    /**
     * @param CategoriesFilterForm $instance
     */
    public static function saveData(CategoriesFilterForm $instance): void
    {

        $profileId = Profile::findByUserId(Yii::$app->user->id)['profile_id'];
        Specialization::deleteAll('profile_id = :profileId', [':profileId' => (int)$profileId]);
        foreach ($instance->categories as $name => $value) {
            if (!$value) {
                continue;
            }
            $specialization = new Specialization();
            $specialization->profile_id = $profileId;
            $specialization->category_id = $name;
            $specialization->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_id', 'category_id'], 'required'],
            [['profile_id', 'category_id'], 'integer'],
            [['profile_id', 'category_id'], 'unique', 'targetAttribute' => ['profile_id', 'category_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile ID',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * {@inheritdoc}
     * @return SpecializationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SpecializationQuery(get_called_class());
    }

    /**
     *
     * @param int $profileId
     * @return array
     */
    public static function findItemsByProfileId(int $profileId): array
    {
        return self::find()->select('c.name, c.id')->from('specialization s')
            ->join('LEFT JOIN', 'profile as p', 's.profile_id = p.id')
            ->join('LEFT JOIN', 'category as c', 's.category_id = c.id')
            ->where(['s.profile_id' => $profileId])->asArray()->all();
    }
}
