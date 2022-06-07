<?php

namespace app\controllers;

use app\components\CalculationsComponent;
use app\components\services\CalculationsService;
use Yii;
use app\models\Calculator;
use yii\web\Controller;

class CalculatorController extends Controller
{
    private $calculationsService;

    public function __construct($id, $module, CalculationsService $calculationsService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->calculationsService = $calculationsService;
    }

    public function actionIndex()
    {
        $model = new Calculator();
        $component = (new CalculationsComponent([], $model, $this->calculationsService))->calculations();
        if (!empty($component['summary']) && !empty($component['paymentPerMonth'])) {
            return $this->render('index', [
                'model' => $model,
                'summary' => $component['summary'],
                'paymentPerMonth' => $component['paymentPerMonth'],
            ]);
        } else {
            return $this->render('index', [
                'model' => $model,
                'summary' => '',
                'paymentPerMonth' => '',
            ]);
        }
    }
}