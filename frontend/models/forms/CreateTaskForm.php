<?php

namespace frontend\models\forms;

use frontend\models\Category;
use frontend\models\File;
use frontend\models\Task;
use TaskForce\Exception\FileException;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;

class CreateTaskForm extends Model
{
    public $name = '';
    public $description = '';
    public $categoryId = '';
    public $files = [];
    public $location = '';
    public $budget = 0;
    public $dateEnd = '';

    public function attributeLabels()
    {
        return [
            'name' => 'Мне нужно',
            'description' => 'Подробности задания',
            'categoryId' => 'Категория',
            'files' => 'Файлы',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'dateEnd' => 'Срок исполнения',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['name', 'description', 'categoryId', 'budget'],
                'required',
                'message' => 'Поле не заполнено.'
            ],
            [['name', 'description'], 'trim'],
            ['name', 'string', 'min' => 10, 'tooShort' => 'Наименование должно быть не менее 10 символов.'],
            ['name', 'string', 'max' => 128, 'tooLong' => 'Наименование должно быть не более 128 символов.'],
            ['description', 'string', 'min' => 30, 'tooShort' => 'Наименование должно быть более 30 символов.'],
            [
                'categoryId',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'message' => 'Введен неверная категория'
            ],
            ['files', 'safe'],
            ['budget', 'integer', 'min' => 0, 'message' => 'Поле должно быть целым положительным числом.'],
            ['dateEnd', 'checkDate'],
            ['dateEnd', 'date', 'format' => 'yyyy-mm-dd'],


        ];
    }

    /**
     * Check data validator
     * @param $attribute
     */
    public function checkDate($attribute): void
    {
        $isOldDate = strtotime('now') > strtotime($this->dateEnd);
        if ($isOldDate) {
            $this->addError($attribute, 'Дата не может быть меньше текущей.');
        }
    }


    /**
     *  Registration users by form data
     * @param int $id
     * @return int|null
     */

    public function saveFields(int $id): ?int
    {
        $task = new Task();
        $task->name = $this->name;
        $task->description = $this->description;
        $task->category_id = $this->categoryId;
        $task->budget = $this->budget;
        $task->expire = ($this->dateEnd === '') ? null : date('Y-m-d H:i:s', strtotime($this->dateEnd));
        $task->date_add = date('Y-m-d H:i:s', time());
        $task->status_id = Task::STATUS_ID_NEW;
        $task->customer_id = $id;
        $task->save();

        return $task->id;
    }

    /**
     * @param int $taskId
     * @return bool|null
     * @throws FileException
     */
    public function uploadFiles(int $taskId): ?bool
    {
        $dirName = Yii::getAlias('@frontend/web/uploads/') . $taskId;
        try {
            FileHelper::createDirectory($dirName);
        } catch (Exception $e) {
            throw new FileException(sprintf('Error create directory: %s', $e->getMessage()));
        }

        foreach ($this->files as $file) {
            $file->saveAs($dirName . '/' . $file->name);

            $taskFileName = new File();
            $taskFileName->filename = $file->name;
            $taskFileName->task_id = $taskId;
            $taskFileName->save();
        }

        return true;
    }

    /**
     * @param int $id
     * @return int|null
     * @throws FileException
     */
    public function saveData(int $id): ?int
    {
        $taskId = $this->saveFields($id);

        if ($taskId) {
            try {
                $this->uploadFiles($taskId);
            } catch (FileException $e) {
                throw new FileException(sprintf('Error save data %s', $e->getMessage()));
            }
        } else {
            return null;
        }
        return $taskId;
    }

}


