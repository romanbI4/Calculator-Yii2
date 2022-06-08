<?php

/* @var $this yii\web\View */
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Все аннуитетные платежи по займу';
?>

<a class="btn btn-primary" href="<?=Url::to(['/calculator/calculate']);?>">Calculate</a>
<?php
if (!empty($dataProvider)) {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
    ]);
}
?>
