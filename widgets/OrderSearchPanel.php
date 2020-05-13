<?php
namespace app\widgets;

use app\models\Order;
use app\models\OrderSearch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class SearchStatuses
 * @package app\widgets
 */
class OrderSearchPanel extends \yii\bootstrap\Widget
{
    public const STATUS_ALL_ORDERS = -1;

    public const SEARCH_STATUSES = [
        self::STATUS_ALL_ORDERS => 'All orders'
    ];

    public const SELECT_OPTION_ORDER_ID = 1;
    public const SELECT_OPTION_LINK     = 2;
    public const SELECT_OPTION_USERNAME = 3;

    public const SELECT_OPTIONS = [
        self::SELECT_OPTION_ORDER_ID => 'Order ID',
        self::SELECT_OPTION_LINK     => 'Link',
        self::SELECT_OPTION_USERNAME => 'Username',
    ];

    /**
     * @var OrderSearch
     */
    public $model;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $requestParams = $this->getRequestParams();
        return $this->render('_search', [
            'model'          => $this->model,
            'searchStatuses' => $this->getSearchStatuses($requestParams),
            'searchOrderParams' => $this->getSearchOrderParams($requestParams),
        ]);
    }

    /**
     * @param array $requestParams
     * @return array
     */
    private function getSearchStatuses(array $requestParams): array
    {
        $statuses = ArrayHelper::merge(self::SEARCH_STATUSES, Order::STATUSES);
        $searchStatuses = [];
        foreach ($statuses as $statusId => $statusName) {
            $paramStatusId = self::STATUS_ALL_ORDERS;
            if (isset($requestParams['status'], Order::STATUSES[$requestParams['status']])) {
                $paramStatusId = $requestParams['status'];
            }

            $active = false;
            if ($statusId === (int)$paramStatusId) {
                $active = true;
            }

            $param = $this->getSearchStatusParam($statusId, $requestParams);

            $searchStatuses[$statusId] = [
                'name'   => $statusName,
                'active' => $active,
                'param'  => $param
            ];
        }

        return $searchStatuses;
    }

    /**
     * @param int $statusId
     * @param array $requestParams
     * @return string
     */
    private function getSearchStatusParam(int $statusId, array $requestParams): string
    {
        $param = [];

        $statuses = array_keys(Order::STATUSES);
        if (in_array($statusId, $statuses, true)) {
            $param[] = "{$this->model->formName()}[status]=$statusId";
        }

        if (isset($requestParams['search'])) {
            $param[] = "{$this->model->formName()}[search]={$requestParams['search']}";
        }

        if (isset($requestParams['search-type'], self::SELECT_OPTIONS[$requestParams['search-type']])) {
            $param[] = "{$this->model->formName()}[search-type]={$requestParams['search-type']}";
        }

        return $param ? '?'.implode('&', $param) : '';
    }

    /**
     * @param array $requestParams
     * @return array
     */
    private function getSearchOrderParams(array $requestParams): array
    {
        $options = [];
        foreach (self::SELECT_OPTIONS as $optionId => $optionName) {
            $paramOptionId = self::SELECT_OPTION_ORDER_ID;
            if (isset($requestParams['search-type'], self::SELECT_OPTIONS[$requestParams['search-type']])) {
                $paramOptionId = $requestParams['search-type'];
            }

            $selected = false;
            if ($optionId === (int)$paramOptionId) {
                $selected = true;
            }

            $options[$optionId] = [
                'name'     => $optionName,
                'selected' => $selected
            ];
        }

        $statusInput = [];
        if (isset($requestParams['status'], Order::STATUSES[$requestParams['status']])) {
            $statusInput = [
                'name' => "{$this->model->formName()}[status]",
                'value' => $requestParams['status'],
            ];
        }

        return [
            'statusInput' => $statusInput,
            'inputName' => "{$this->model->formName()}[search]",
            'selectName' => "{$this->model->formName()}[search-type]",
            'selectOptions' => $options
        ];
    }

    /**
     * @return array
     */
    private function getRequestParams(): array
    {
        $requestParams = Yii::$app->request->get();
        return $requestParams[$this->model->formName()] ?? [];
    }
}
