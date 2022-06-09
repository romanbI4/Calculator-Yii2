<?php

namespace app\components\repositories;

use app\models\Calculations;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\base\Exception as BaseException;

class CalculationsRepository
{
    public function getList(): ActiveQuery
    {
        if (!$list = Calculations::find()) {
            throw new Exception('Calculations is not found.');
        }
        return $list;
    }

    public function getListByParams($params = [])
    {
        if (is_array($params) && !empty($params)) {
            if (!$list = Calculations::find()->where([
                    'monthly_payment' => $params['summary'],
                    'date' => $params['dates']
                ])
            ) {
                throw new Exception('Calculations with this params not found.');
            }
            return $list;
        } else {
            throw new BaseException('Invalid params');
        }
    }

}