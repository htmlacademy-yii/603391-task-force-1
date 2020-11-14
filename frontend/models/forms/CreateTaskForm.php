<?php

namespace frontend\models\forms;

use frontend\models\Category;
use frontend\models\City;
use frontend\models\File;
use frontend\models\Task;
use TaskForce\Exception\FileException;
use TaskForce\GeoCoder;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;

class CreateTaskForm extends Model
{
    public const NOT_CORRECT_CITY = 'Не допустимый город в адресе';
    public const NOT_CORRECT_LOCATION = 'Не допустимый адрес';

    public string $name = '';
    public string $description = '';
    public string $categoryId = '';
    public array  $files = [];
    public string $location = '';
    public string $city = '';
    public string $lat = '';
    public string $lng = '';
    public int    $budget = 0;
    public string $dateEnd = '';

    public function attributeLabels()
    {
        return [
            'name' => 'Мне нужно',
            'description' => 'Подробности задания',
            'categoryId' => 'Категория',
            'files' => 'Файлы',
            'location' => 'Локация',
            'city' => 'Город',
            'lat' => 'Долгота',
            'lng' => 'Широта',
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
            [
                'city',
                'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'city',
                'message' => self::NOT_CORRECT_CITY
            ],
            ['files', 'safe'],
            ['location', 'string', 'max' => 255, 'tooLong' => 'Поле Локация должно быть не более 255 символов.'],
            ['lat', 'match', 'pattern' => '/^\d{1,2}.\d{6}$/D', 'message' => self::NOT_CORRECT_LOCATION],
            ['lng', 'match', 'pattern' => '/^\d{1,3}.\d{6}$/D', 'message' => self::NOT_CORRECT_LOCATION],
            ['budget', 'integer', 'min' => 0, 'message' => 'Поле должно быть целым положительным числом.'],
            ['dateEnd', 'date', 'format' => 'yyyy-mm-dd'],
            ['dateEnd', 'isDateInFuture'],
        ];
    }

    /**
     * data validator
     * @param $attribute
     */
    public function isDateInFuture($attribute): void
    {
        $isPastDate = strtotime('now') > strtotime($this->$attribute);
        if ($isPastDate) {
            $this->addError($attribute, 'Дата не может быть меньше текущей.');
        }
    }

    /**
     *  Registration users by form data
     * @param int $customerId
     * @return int|null
     */

    public function saveFields(int $customerId): ?int
    {
        $task = new Task();
        $task->name = $this->name;
        $task->description = $this->description;
        $task->category_id = $this->categoryId;
        $task->budget = $this->budget;
        $task->expire = ($this->dateEnd === '') ? null : date('Y-m-d H:i:s', strtotime($this->dateEnd));
        $task->date_add = date('Y-m-d H:i:s', time());
        $task->status = TaskEntity::STATUS_NEW;
        $task->customer_id = $customerId;
        $task->address = $this->location;

        if ($this->lat && $this->lng && $this->city !== '') {
            $task->lat = $this->lat;
            $task->lng = $this->lng;
            $task->city_id = City::findIdByName($this->city);
        } else {
            $geoCoder = new GeoCoder();
            $coordinates = $geoCoder->getCoordinates($this->location);
            $task->lat = $coordinates['lat'];
            $task->lng = $coordinates['lng'];
            $task->city_id = City::findIdByName($coordinates['city']);
        }

        try {
            $task->save();
        } catch (\Exception $e) {
            self::addError(' ', $e->getMessage());
        }

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
            $newFilename = substr(md5(microtime() . rand(0, 9999)), 0, 20) . '.' . $file->extension;
            $file->saveAs($dirName . '/' . $newFilename);

            $taskFileName = new File();
            $taskFileName->filename = $file->name;
            $taskFileName->generated_name = $newFilename;
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
