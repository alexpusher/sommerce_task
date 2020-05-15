<?php
namespace app\widgets;

use app\models\Order;
use app\models\OrderSearch;
use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class SearchStatuses
 * @package app\widgets
 */
class OrderSearchPanel extends Widget
{
    public const STATUS_ALL_ORDERS = -1;

    public const SEARCH_STATUSES = [
        self::STATUS_ALL_ORDERS => 'All orders'
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
            'model'             => $this->model,
            'searchStatuses'    => $this->getSearchStatusesStructure($requestParams),
            'searchOrderParams' => $this->getSearchOrderStructure($requestParams),
        ]);
    }

    /**
     * @param array $requestParams
     * @return array
     */
    private function getSearchStatusesStructure(array $requestParams): array
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
        $params = [];

        $statuses = array_keys(Order::STATUSES);
        if (in_array($statusId, $statuses, true)) {
            $params[] = "{$this->model->formName()}[status]=$statusId";
        }

        if (isset($requestParams['search']) && $requestParams['search'] !== '') {
            $params[] = "{$this->model->formName()}[search]={$requestParams['search']}";
        }

        if (isset($requestParams['search-type'], OrderSearch::SELECT_OPTIONS[$requestParams['search-type']])) {
            $params[] = "{$this->model->formName()}[search-type]={$requestParams['search-type']}";
        }

        return $params ? '?'.implode('&', $params) : '';
    }

    /**
     * @param array $requestParams
     * @return array
     */
    private function getSearchOrderStructure(array $requestParams): array
    {
        $options = [];
        $orderOptions = OrderSearch::SELECT_OPTIONS;
        foreach ($orderOptions as $optionId => $optionName) {
            $paramOptionId = OrderSearch::SELECT_OPTION_ORDER_ID;
            if (isset($requestParams['search-type'], $orderOptions[$requestParams['search-type']])) {
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
