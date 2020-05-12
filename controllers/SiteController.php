<?php

namespace app\controllers;

use Yii;
use yii\data\SqlDataProvider;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $totalOrdersCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM orders')->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT * FROM orders',
            'params' => [],
            'totalCount' => $totalOrdersCount,
            'sort' => [
                'attributes' => [
                    'id'
                ],
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        return $this->render('orders', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
