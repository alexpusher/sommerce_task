<?php

/* @var $this View */
/* @var SqlDataProvider $dataProvider */
/* @var OrderSearch $searchModel
 */

use app\models\Order;
use app\models\OrderSearch;
use app\widgets\OrderSearchPanel;
use yii\data\SqlDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

$orderStatuses = Order::STATUSES;
$orderModes = Order::MODES;
$getParams = Yii::$app->request->get();
?>
<?= OrderSearchPanel::widget(['model' => $searchModel])?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pager' => ['maxButtonCount' => 10],
    'columns' => [
        [
            'label' => Yii::t('app', 'ID'),
            'attribute' => 'id',
        ],
        [
            'label' => Yii::t('app', 'User'),
            'attribute' => 'user',
        ],
        [
            'label' => Yii::t('app', 'Link'),
            'attribute' => 'link',
        ],
        [
            'label' => Yii::t('app', 'Quantity'),
            'attribute' => 'quantity',
        ],
        [
            'label' => Yii::t('app', 'Service'),
            'attribute' => 'service.name',
            /*'value' => static function($data) {
                return date('Y-m-d H:i:s', $data['created_at']);
            },*/
        ],
        [
            'label' => Yii::t('app', 'Status'),
            'attribute' => 'status',
            'value' => static function($data) use($orderStatuses) {
                return $orderStatuses[$data['status']];
            },
        ],
        [
            'label' => Yii::t('app', 'Mode'),
            'attribute' => 'mode',
            'value' => static function($data) use($orderModes) {
                return $orderModes[$data['mode']];
            },
            'filter' => Html::activeDropDownList($searchModel, 'mode', $orderModes, ['class'=>'form-control','prompt' => 'All'])
        ],
        [
            'label' => Yii::t('app', 'Created'),
            'attribute' => 'created_at',
            'value' => static function($data) {
                return date('Y-m-d H:i:s', $data['created_at']);
            },
        ],
    ]
])
?>