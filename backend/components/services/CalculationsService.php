<?php

namespace app\components\services;

use app\components\repositories\CalculationsRepository;
use app\models\Calculations;

class CalculationsService
{
    public $calculationsRepository;

    public function __construct(CalculationsRepository $calculationsRepository)
    {
        $this->calculationsRepository = $calculationsRepository;
    }

    public function getList() {
        $list = $this->calculationsRepository->getList();

        return $list;
    }

    public function getListByParams($params = [])
    {
        $list = $this->calculationsRepository->getListByParams($params);

        return $list;
    }

    public function save(array $data)
    {
        $model = new Calculations();
        $model->date = $data[0];
        $model->monthly_payment = $data[1];
        $model->amount_of_interest_paid = $data[2];
        $model->principal_amount_to_be_repaid = $data[3];
        $model->balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment = $data[4];
        $model->save();
    }
}