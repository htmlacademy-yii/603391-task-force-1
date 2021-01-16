<?php

namespace frontend\models;

use TaskForce\Exception\FileException;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "work".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $filename
 * @property string|null $generated_name
 *
 * @property User $user
 */
class Work extends ActiveRecord
{
    /**
     * @var mixed|string|null
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work';
    }

    public static function saveFile(?UploadedFile $file): string
    {
        $userId = Yii::$app->user->getId();
        $ds = DIRECTORY_SEPARATOR;
        $workDir = Yii::$app->params['uploadsDir'] . $ds . Yii::$app->params['worksDir'];
        $uploadUserDir = $workDir . $ds . $userId;
        $countFiles = Work::find()->where(['user_id'=>$userId])->count();
        if ($countFiles > Yii::$app->params['maxWorksFiles'] - 1) {
            throw new Exception('Limit of files is exceeded');
        }
        try {
            FileHelper::createDirectory($uploadUserDir);
        } catch (Exception $e) {
            throw new FileException(sprintf('Error create directory: %s', $e->getMessage()));
        }
        $newFileName = uniqid() . '.' . $file->extension;
        $file->saveAs($uploadUserDir . $ds . $newFileName);
        $work = new Work();
        $work->user_id = Yii::$app->user->getId();
        $work->filename = $file->name;
        $work->generated_name = $newFileName;
        $work->save();

        return $newFileName;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['filename'], 'string', 'max' => 512],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
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
            'user_id' => 'User ID',
            'filename' => 'Filename',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return WorkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkQuery(get_called_class());
    }

    /**
     *
     * @param int $id
     * @return  array
     */
    public static function findWorkFilesByUserId(int $id): array
    {
        return self::find()->where(['user_id' => $id])->asArray()->limit(5)->all();
    }
}
