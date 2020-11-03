<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "favorite".
 *
 * @property int $user_id
 * @property int $favorite_id
 *
 * @property File $favorite
 * @property User $user
 */
class Favorite extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'favorite_id'], 'required'],
            [['user_id', 'favorite_id'], 'integer'],
            [['user_id', 'favorite_id'], 'unique', 'targetAttribute' => ['user_id', 'favorite_id']],
            [['favorite_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['favorite_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'favorite_id' => 'Favorite ID',
        ];
    }

    /**
     * Gets query for [[Favorite]].
     *
     * @return \yii\db\ActiveQuery|FileQuery
     */
    public function getFavorite()
    {
        return $this->hasOne(File::className(), ['id' => 'favorite_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return FavoriteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FavoriteQuery(get_called_class());
    }
}
