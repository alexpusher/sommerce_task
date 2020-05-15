<?php

namespace app\controllers;

use app\models\Order;
use app\models\OrderSearch;
use app\models\Service;
use Yii;
use yii\web\Controller;

/**
 * Class OrdersController
 * @package app\controllers
 */
class OrdersController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('orders', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return bool
     */
    public function actionDownload(): bool
    {

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $orders = $dataProvider->query->all();
        $orderHeaderRows = $searchModel->getAttributes();
        $formattedOrders[] = array_keys($orderHeaderRows);
        /** @var Order $order */
        foreach ($orders as $order) {
            $formattedOrders[] = array_values($order->toArray());
        }

        $fp = fopen(Order::FILE_NAME, 'wb');
        foreach ($formattedOrders as $formattedOrder) {
            fputcsv($fp, $formattedOrder);
        }

        fclose($fp);

        return Yii::$app->response->sendFile(Order::FILE_NAME, Order::FILE_NAME, [
            'mimeType' => 'application/csv',
            'inline'   => false
        ])->send() and unlink(Order::FILE_NAME);
    }
}
