<?php

namespace app\controllers;

use Yii;
use app\models\Calculator;
use yii\web\Controller;

class CalculatorController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Calculator();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) { 
            //формула
            return $this->render('index', ['model' => $model]);
        }
        else {
            return $this->render('index', ['model' => $model]);
        }
    }

}
