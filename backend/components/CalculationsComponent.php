<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\db\Exception;

class CalculationsComponent extends Component
{
    public string $summary;
    public string $paymentPerMonth;
    public $model;
    private $calculationsService;

    public function __construct(
        $config,
        $model,
        $calculationsService
    ) {
        parent::__construct($config);
        $this->model = $model;
        $this->calculationsService = $calculationsService;
    }

    public function calculations()
    {
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $summary = round(((($this->model->annualRate / 100 / 12) * pow((1 + ($this->model->annualRate / 100 / 12)), $this->model->longTerm)) / (pow((1 + ($this->model->annualRate / 100 / 12)), $this->model->longTerm) - 1)) * $this->model->allSumm, 2);
            $paymentPerMonth = ($this->model->longTerm * $summary) - $this->model->allSumm;
            $dates = [];
            for ($i = 0; $i < (int)$this->model->longTerm; $i++) {
                $diffArr[$i] = [
                    $diffArr[$i]["date"] = $this->model->startDate,
                    $diffArr[$i]["monthly_payment"] = $summary,
                    $diffArr[$i]["amount_of_interest_paid"] = round($this->model->allSumm * ($this->model->annualRate / 100 / $this->model->annualRate), 2),
                    $diffArr[$i]['principal_amount_to_be_repaid'] = round($diffArr[$i]["monthly_payment"] - $diffArr[$i]["amount_of_interest_paid"], 2),
                    $diffArr[$i]['balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'] = round($this->model->allSumm - $diffArr[$i]['principal_amount_to_be_repaid'], 2),
                ];
                $dates[] = $diffArr[$i][0];
                if ($i >= 1) {
                    $diffArr[$i] = [
                        $diffArr[$i]["date"] = date('d.m.Y', strtotime($this->model->startDate . ' + ' . $i . ' month')),
                        $diffArr[$i]["monthly_payment"] = $summary,
                        $diffArr[$i]["amount_of_interest_paid"] = round($diffArr[$i - 1][4] * ($this->model->annualRate / 100 / $this->model->annualRate), 2),
                        $diffArr[$i]['principal_amount_to_be_repaid'] = round(($diffArr[$i - 1][2] - $diffArr[$i]["amount_of_interest_paid"]) + $diffArr[$i - 1][3], 2),
                        $diffArr[$i]['balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'] = round($diffArr[$i - 1][4] - $diffArr[$i]['principal_amount_to_be_repaid'], 2),
                    ];
                    $dates[] = $diffArr[$i][0];
                }

                try {
                    $this->calculationsService->save($diffArr[$i]);
                } catch (Exception $exception) {
                    return $exception->getMessage();
                }
            }

            $query = $this->calculationsService->getListByParams([
                'summary' => $summary,
                'dates' => array_unique($dates),
            ]);

            return [
                'summary' => $summary,
                'paymentPerMonth' => $paymentPerMonth,
                'model' => $this->model,
                'query' => $query
            ];
        }
    }
}