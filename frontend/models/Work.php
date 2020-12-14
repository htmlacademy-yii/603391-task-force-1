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

    public static function findAllFiles($dir): array
    {
        $root = scandir($dir);
        $result = [];
        foreach ($root as $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            if (is_file("$dir/$value")) {
                $result[] = "$dir/$value";
                continue;
            }
            foreach (self::findAllFiles("$dir/$value") as $item) {
                $result[] = $item;
            }
        }

        return $result;
    }

    public static function saveFile(?UploadedFile $file): string
    {
        $ds = DIRECTORY_SEPARATOR;
        $upload = Yii::$app->params['uploadsDir'] . $ds . Yii::$app->params['worksDir'];
        $uploadDir = $upload . $ds . Yii::$app->user->getId();
        $countFiles = count(self::findAllFiles($uploadDir));
        if ($countFiles > Yii::$app->params['maxWorksFiles'] - 1) {
            throw new Exception('Error');
        }
        try {
            FileHelper::createDirectory($uploadDir);
        } catch (Exception $e) {
            throw new FileException(sprintf('Error create directory: %s', $e->getMessage()));
        }

        $newFileName =  uniqid() . '.' . $file->extension;
        $file->saveAs($uploadDir . $ds . $newFileName);
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
