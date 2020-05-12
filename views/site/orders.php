<?php

/* @var $this yii\web\View */
/* @var SqlDataProvider $dataProvider */

use app\app\response\Dtos\OrderDTO;
use app\models\Order;
use yii\data\SqlDataProvider;
use yii\grid\GridView;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

$orderStatuses = Order::STATUSES;
$orderModes = Order::MODES;
?>
<ul class="nav nav-tabs p-b">
    <li class="active"><a href="/orders">All orders</a></li>
    <?php foreach($orderStatuses as $index => $orderStatus): ?>
        <li><a href="/orders?status=<?= $index?>"><?= $orderStatus?></a></li>
    <?php endforeach; ?>
    <li class="pull-right custom-search">
        <form class="form-inline" action="/orders" method="get">
            <div class="input-group">
                <input type="text" name="search" class="form-control" value="" placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">

            <select class="form-control search-select" name="search-type">
              <option value="1" selected="">Order ID</option>
              <option value="2">Link</option>
              <option value="3">Username</option>
            </select>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
            </span>
            </div>
        </form>
    </li>
</ul>

<?=
GridView::widget([
        'dataProvider' => $dataProvider,
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
                'attribute' => 'service_id',
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'attribute' => 'status',
                'value' => static function($data) use($orderStatuses) {
                    return $orderStatuses[$data['status']];
                },
            ],
            [
                'attribute' => 'mode',
                'value' => static function($data) use($orderModes) {
                    return $orderModes[$data['mode']];
                },
                'filter' => ['-1' => 'All', '0' => 'Manual', '1' => 'Auto']
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