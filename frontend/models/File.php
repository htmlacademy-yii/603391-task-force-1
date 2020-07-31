<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property int $task_id
 * @property string $filename
 *
 * @property Favorite[] $favorites
 * @property User[] $users
 * @property Task $task
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'filename'], 'required'],
            [['task_id'], 'integer'],
            [['filename'], 'string', 'max' => 512],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'filename' => 'Filename',
        ];
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery|FavoriteQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::className(), ['favorite_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('favorite', ['favorite_id' => 'id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }
}
