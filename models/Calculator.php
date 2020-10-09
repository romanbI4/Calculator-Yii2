<?php

namespace app\models;

use yii\base\Model;

class Calculator extends Model
{
    public $startDate;
    public $allSumm;
    public $longTerm;
    public $annualRate;
    
    public function rules()
    {
        return [
            [['startDate'], 'string'],
            [['allSumm'], 'integer'],
            [['longTerm'], 'integer'],
            [['annualRate'], 'integer'],
            [['startDate', 'allSumm', 'longTerm', 'annualRate'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'startDate' => 'Начальная дата:',
            'allSumm' => 'Сумма займа:',
            'longTerm' => 'Срок займа (в месяцах): ',
            'annualRate' => 'Годовая процентная ставка',
        ];
    }
}