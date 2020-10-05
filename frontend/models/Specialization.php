<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "specialization".
 *
 * @property int $profile_id
 * @property int $category_id
 *
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
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategory()
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
     * @param int $userId
     * @return array|Specialization[]
     */
    public static function findSpecializationByUserId(int $userId)
    {
        return self::find()->select('c.name, c.id')->from('specialization s')
            ->join('LEFT JOIN', 'profile as p', 's.profile_id = p.id')
            ->join('LEFT JOIN', 'category as c', 's.category_id = c.id')
            ->where(['p.id' => $userId])->asArray()->all();
    }

}
