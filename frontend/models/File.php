<?php

namespace frontend\models;

use TaskForce\Exception\FileException;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property int $task_id
 * @property string $filename
 * @property string $generated_name
 *
 * @property Favorite[] $favorites
 * @property User[] $users
 * @property Task $task
 */
class File extends ActiveRecord
{
    use ExceptionOnFindFail;

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
            [['task_id', 'filename', 'generated_name'], 'required'],
            [['task_id'], 'integer'],
            [['filename', 'generated_name'], 'string', 'max' => 512],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
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
            'generated_name' => 'Generated name',
        ];
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return ActiveQuery|FavoriteQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['favorite_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery|UserQuery
     * @throws InvalidConfigException
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('favorite', ['favorite_id' => 'id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery|TaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public static function findByTaskID(int $id): array
    {
        return self::find()->where(['task_id' => $id])->asArray()->all();
    }


    public static function forceDownloadTaskFile(int $id): void
    {
        $taskFile = self::findOrFail($id, 'The file is not definable.');

        $file = __DIR__ . sprintf('/../web/uploads/%s/%s', $taskFile->task_id, $taskFile->generated_name);

        if (file_exists($file)) {
            Yii::$app->response->sendFile($file, $taskFile->filename);
        } else {
            throw new FileException('File not found.');
        }
    }
}
