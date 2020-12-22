<?php

/** @var array $categories */
/** @var CreateTaskForm $createTaskForm */
/** @var array $cities */

use frontend\assets\TaskCreateAsset;
use frontend\models\forms\CreateTaskForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$apiKey = Yii::$app->params['yandex_api_key'];
$yandexApiJs = "https://api-maps.yandex.ru/2.1/?apikey=$apiKey&lang=ru_RU";
$this->registerJSFile($yandexApiJs, $options = [$position = yii\web\View::POS_HEAD]);

TaskCreateAsset::register($this);
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="create__task">
            <h1>Публикация нового задания</h1>
            <div class="create__task-main">
                <?php
                $form = ActiveForm::begin(
                    [
                        'id' => 'task-form',
                        'enableClientValidation' => false,
                        'fieldConfig' => [
                            'inputOptions' => ['class' => 'input textarea'],
                            'errorOptions' => ['tag' => 'span'],
                            'hintOptions' => ['tag' => 'span'],
                        ],
                        'options' => [
                            'class' => 'create__task-form form-create',
                            'enctype' => 'multipart/form-data'
                        ]
                    ]
                );

                echo $form
                    ->field($createTaskForm, 'name', ['options' => ['tag' => false, 'id' => 'name']])
                    ->label('Мне нужно')
                    ->textarea(
                        [
                            'rows' => 1,
                            'placeholder' => 'Повесить полку',
                            'autofocus' => true,

                        ]
                    )
                    ->hint('Кратко опишите суть работы');

                echo $form
                    ->field($createTaskForm, 'description', ['options' => ['tag' => false]])
                    ->label('Подробности задания')
                    ->textarea(
                        [
                            'rows' => 7,
                            'placeholder' => 'Place your text',
                        ]
                    )
                    ->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться');

                echo $form
                    ->field($createTaskForm, 'categoryId', ['options' => ['tag' => false]])
                    ->label('Категория')
                    ->dropDownList(
                        $categories,
                        [
                            'class' => 'multiple-select input multiple-select-big',
                            'size' => 1
                        ]
                    )
                    ->hint('Выберите категорию'); ?>

                <label>Файлы</label>
                <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>

                <?= $form->field(
                    $createTaskForm,
                    'files[]',
                    [
                        'options' => ['tag' => 'div', 'for' => 'file', 'class' => 'create__file input'],
                    ]
                )
                    ->fileInput(
                        [
                            'class' => 'dropzone visually-hidden',
                            'multiple' => 'true',
                            'id' => 'file'
                        ]
                    )
                    ->label('Добавить новый файл') ?>


                <?= $form
                    ->field(
                        $createTaskForm,
                        'location',
                        ['options' => ['tag' => false]]
                    )
                    ->label('Локация')
                    ->input(
                        'search',
                        [
                            'id' => 'autoComplete',
                            'tabindex' => '1',
                            'placeholder' => 'Санкт-Петербург, Калининский район',
                            'class' => 'input-navigation input-middle input',
                        ]
                    )
                    ->hint('Укажите адрес исполнения, если задание требует присутствия'); ?>
                <?= $form->field($createTaskForm, 'city')
                    ->label(false)
                    ->hiddenInput(['value' => '', 'id' => 'city']); ?>
                <?= $form->field($createTaskForm, 'lat')
                    ->label(false)
                    ->hiddenInput(['value' => '', 'id' => 'lat']); ?>
                <?= $form->field($createTaskForm, 'lng')
                    ->label(false)
                    ->hiddenInput(['value' => '', 'id' => 'lng']); ?>

                <div class="create__price-time">

                    <?= $form->field(
                        $createTaskForm,
                        'budget',
                        [
                            'options' => [
                                'tag' => 'div',
                                'class' => 'create__price-time--wrapper'
                            ]
                        ]
                    )
                        ->label('Бюджет')
                        ->textarea(
                            [
                                'rows' => 1,
                                'placeholder' => '1000',
                                'class' => 'input textarea input-money'
                            ]
                        )
                        ->hint('Не заполняйте для оценки исполнителем'); ?>

                    <?= $form->field(
                        $createTaskForm,
                        'dateEnd',
                        [
                            'options' => [
                                'tag' => 'div',
                                'class' => 'create__price-time--wrapper'
                            ]
                        ]
                    )
                        ->label('Срок исполнения')
                        ->input(
                            'date',
                            [
                                'rows' => 1,
                                'placeholder' => '10.11, 15:00',
                                'class' => 'input-middle input input-date',
                            ]
                        )
                        ->hint('Укажите крайний срок исполнения'); ?>
                </div>

                <?php
                ActiveForm::end() ?>

                <div class="create__warnings">
                    <div class="warning-item warning-item--advice">
                        <h2>Правила хорошего описания</h2>
                        <h3>Подробности</h3>
                        <p>Друзья, не используйте случайный<br>
                            контент – ни наш, ни чей-либо еще. Заполняйте свои
                            макеты, вайрфреймы, мокапы и прототипы реальным
                            содержимым.</p>
                        <h3>Файлы</h3>
                        <p>Если загружаете фотографии объекта, то убедитесь,
                            что всё в фокусе, а фото показывает объект со всех
                            ракурсов.</p>
                    </div>

                    <?php
                    if (!empty($createTaskForm->errors)) : ?>
                        <div class="warning-item warning-item--error">
                            <h2>Ошибки заполнения формы</h2>
                            <?php
                            foreach ($createTaskForm->errors as $attribute => $errors) : ?>
                                <h3><?= $createTaskForm->getAttributeLabel($attribute) ?></h3>
                                <p>
                                    <?php
                                    foreach ($errors as $error) : ?>
                                        <?= $error ?><br>
                                    <?php
                                    endforeach; ?>
                                </p>
                            <?php
                            endforeach; ?>
                        </div>
                    <?php
                    endif; ?>
                </div>
            </div>
            <?php
            echo Html::submitButton(
                'Опубликовать',
                ['class' => 'button', 'id' => 'submit-all', 'form' => 'task-form']
            ); ?>
        </section>
    </div>
</main>



