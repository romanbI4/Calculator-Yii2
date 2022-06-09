<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use scotthuangzl\googlechart\GoogleChart;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = 'Калькулятор аннуитетных платежей по займу';

$form = ActiveForm::begin([
    'id' => 'calculator-form',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-0\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-6 control-label'],
    ],
]); ?>

    <?= $form->field($model, 'startDate')->widget(DatePicker::class, [
        'name' => 'startDate',
        'type' => DatePicker::TYPE_INPUT,
        'options' => ['placeholder' => 'Выберите начальную дату...'],
        'convertFormat' => true,
        'value'=> date("d.m.Y", (strtotime($model->startDate))),
        'pluginOptions' => [
            'format' => 'dd.MM.yyyy',
            'autoclose'=>true,
            'weekStart'=>1,
            'startDate' => '01.01.2000',
            'todayBtn'=>true,
        ]
    ]);
    ?>
    <?= $form->field($model, 'allSumm')?>
    <?= $form->field($model, 'longTerm')?>
    <?= $form->field($model, 'annualRate')?>

    <div class="form-group">
        <?= Html::submitButton('Выполнить расчет', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php if (!empty($model->startDate) && !empty($summary) && !empty($paymentPerMonth)) {?>
<div class="row">
    <div class="col">
        <label>Начальная дата:</label>: <?= Html::encode($model->startDate) ?>
    </div>
    <div class="col">
        <label>Аннуитетный платеж:</label>: <?= Html::encode($summary) ?>
    </div>
    <div class="col">
        <label>Платеж в месяц:</label>: <?= Html::encode($paymentPerMonth) ?>
    </div>
</div>
<?php } ?>

<a class="btn btn-primary" href="<?=Url::to(['/calculator/index']);?>">Main page</a>

<?php 
if (!empty($model->allSumm) && !empty($paymentPerMonth)) {
    echo GoogleChart::widget(array('visualization' => 'PieChart',
        'data' => array(
            array('Task', 'All Statuses'),
            array('Сумма Кредита', (int) "$model->allSumm"),
            array('Общая переплата', (int) "$paymentPerMonth"),
        ),
        'options' => array('title' => 'Платежи')));
}

if (!empty($summary) && !empty($model->longTerm) && isset($query)) {
    $diffPayment = new ActiveDataProvider([
        'query' => $query
    ]);
}

if (!empty($diffPayment)) {
    echo GridView::widget([
        'dataProvider' => $diffPayment,
    ]);
}
?>
