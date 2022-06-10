<?php

use yii\db\Migration;

class m220610_114520_create_calculations_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%calculations}}', [
            'id' => $this->primaryKey(),
            'date' => $this->string(),
            'monthly_payment' => $this->double(),
            'amount_of_interest_paid' => $this->double(),
            'principal_amount_to_be_repaid' => $this->double(),
            'balance_of_the_principal_debt_on_the_loan_at_the_date_of_payment' => $this->double()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%calculations}}');
    }
}
