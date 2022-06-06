<?php 

namespace app\models;

use yii\base\Model;

class Calculations extends \yii\db\ActiveRecord
{
    
    static public function tableName()
    {
        return "{{%calculations}}";
    }

    public function rules()
    {
        return [
            [['date'], 'string'],
            [['monthly_payment'], 'real'],
            [['amount_of_interest_paid'], 'real'],
            [['principal_amount_to_be_repaid'], 'real'],
            [['balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'], 'real'],
            [['date', 'monthly_payment', 'amount_of_interest_paid','principal_amount_to_be_repaid', 'balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Номер платежа',
            'date' => 'Дата платежа',
            'monthly_payment' => 'Ежемесячный платеж',
            'amount_of_interest_paid' => 'Сумма погашаемых процентов',
            'principal_amount_to_be_repaid' => 'Сумма погашаемого основного долга',
            'balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment' => 'Остаток основного долга по займу на текущую дату',
        ];
    }
}

?>