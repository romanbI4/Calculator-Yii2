<?php

namespace app\components\services;

use Yii;

class CalculationsService
{
    public function save(array $data)
    {
        Yii::$app->db->createCommand()->batchInsert('calculations', ['date', 'monthly_payment', 'amount_of_interest_paid', 'principal_amount_to_be_repaid', 'balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'], [
            [$data[0], $data[1], $data[2], $data[3], $data[4]],
        ])->execute();
    }
}