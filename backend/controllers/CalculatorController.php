<?php

namespace app\controllers;

use Yii;
use app\models\Calculator;
use yii\web\Controller;

class CalculatorController extends Controller
{

    public string $summary;
    public string $paymentPerMonth;
    public string $diffPayment;

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
    
    public function actionIndex()
    {
        $model = new Calculator();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $summary = round(((($model->annualRate / 100 / 12) * pow((1 + ($model->annualRate / 100 / 12)), $model->longTerm)) / (pow((1+($model->annualRate / 100 / 12)), $model->longTerm)-1)) * $model->allSumm, 2);
            $paymentPerMonth = ($model->longTerm * $summary) - $model->allSumm;
            for ($i = 0; $i < (int)$model->longTerm; $i++) {
                $diffArr[$i] = [
                    $diffArr[$i]["date"] = $model->startDate,
                    $diffArr[$i]["monthly_payment"] = $summary,
                    $diffArr[$i]["amount_of_interest_paid"] = round($model->allSumm * ($model->annualRate / 100 / $model->annualRate), 2),
                    $diffArr[$i]['principal_amount_to_be_repaid'] = round($diffArr[$i]["monthly_payment"] - $diffArr[$i]["amount_of_interest_paid"], 2),
                    $diffArr[$i]['balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'] = round($model->allSumm - $diffArr[$i]['principal_amount_to_be_repaid'], 2),
                ];
                if ($i >= 1) {   
                    $diffArr[$i] = [
                        $diffArr[$i]["date"] = date('d.m.Y',strtotime($model->startDate . ' + ' . $i  . ' month')),
                        $diffArr[$i]["monthly_payment"] = $summary,
                        $diffArr[$i]["amount_of_interest_paid"] = round($diffArr[$i - 1][4] * ($model->annualRate / 100 / $model->annualRate), 2),
                        $diffArr[$i]['principal_amount_to_be_repaid'] = round(($diffArr[$i - 1][2] - $diffArr[$i]["amount_of_interest_paid"]) + $diffArr[$i - 1][3], 2),
                        $diffArr[$i]['balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'] = round($diffArr[$i - 1][4] - $diffArr[$i]['principal_amount_to_be_repaid'], 2),
                    ]; 
                }
                Yii::$app->db->createCommand()->batchInsert('calculations', ['date', 'monthly_payment', 'amount_of_interest_paid' , 'principal_amount_to_be_repaid', 'balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'], [
                    [$diffArr[$i][0], $diffArr[$i][1], $diffArr[$i][2], $diffArr[$i][3], $diffArr[$i][4]],
                ])->execute();
            }
            return $this->render('index', [
                'model' => $model,
                'summary' => $summary,
                'paymentPerMonth' => $paymentPerMonth,
            ]);
        }
        else {
            return $this->render('index', [
                'model' => $model,
                'summary' => '',
                'paymentPerMonth' => '',
            ]);
        }
    }

}
