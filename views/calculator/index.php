<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Калькулятор аннуитетных платежей по займу';

$form = ActiveForm::begin([
    'id' => 'calculator-form',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-0\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-6 control-label'],
    ],
]); ?>

    <?= $form->field($model, 'startDate') ?>
    <?= $form->field($model, 'allSumm') ?>
    <?= $form->field($model, 'longTerm') ?>
    <?= $form->field($model, 'annualRate') ?>

    <div class="form-group">
        <?= Html::submitButton('Выполнить расчет', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>