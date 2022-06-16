<?php

namespace app\controllers;

use app\components\CalculationsComponent;
use app\components\services\CalculationsService;
use app\models\Calculator;
use yii\data\ActiveDataProvider;
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
        $dataProvider = new ActiveDataProvider([
            'query' => $this->calculationsService->getList(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCalculate()
    {
        $model = new Calculator();
        $component = (new CalculationsComponent([], $model, $this->calculationsService))->calculations();
        if (!empty($component['summary']) && !empty($component['paymentPerMonth']) && !empty($component['query'])) {
            return $this->render('calculate', [
                'model' => $model,
                'summary' => $component['summary'],
                'paymentPerMonth' => $component['paymentPerMonth'],
                'query' => $component['query']
            ]);
        } else {
            return $this->render('calculate', [
                'model' => $model,
                'summary' => '',
                'paymentPerMonth' => '',
            ]);
        }
    }
}