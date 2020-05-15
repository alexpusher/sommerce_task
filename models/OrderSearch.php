<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Exception;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $user
 * @property string $link
 * @property int $quantity
 * @property int $service_id
 * @property int $status 0 - Pending, 1 - In progress, 2 - Completed, 3 - Canceled, 4 - Fail
 * @property int $created_at
 * @property int $mode 0 - Manual, 1 - Auto
 *
 * @property Service $service
 */
class OrderSearch extends Order
{
    public const SELECT_OPTION_ORDER_ID = 1;
    public const SELECT_OPTION_LINK     = 2;
    public const SELECT_OPTION_USERNAME = 3;

    public const SELECT_OPTIONS = [
        self::SELECT_OPTION_ORDER_ID => 'Order ID',
        self::SELECT_OPTION_LINK     => 'Link',
        self::SELECT_OPTION_USERNAME => 'Username',
    ];

    public const PAGE_SIZE = 100;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id','mode'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Order::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $params = $params[$this->formName()];

        $query->andFilterWhere(['mode' => $this->mode]);
        $query->andFilterWhere(['service_id' => $this->service_id]);

        if (isset($params['status'], self::STATUSES[$params['status']])) {
            $query->andWhere('status = :status', [':status' => $params['status']]);
        }

        if (isset(
            $params['search'],
            $params['search-type'],
            self::SELECT_OPTIONS[$params['search-type']]
        ) && $params['search'] !== '') {
            switch ($params['search-type']) {
                case self::SELECT_OPTION_ORDER_ID:
                    $rowName = 'id';
                    break;
                case self::SELECT_OPTION_LINK:
                    $rowName = 'link';
                    break;
                case self::SELECT_OPTION_USERNAME:
                    $rowName = 'user';
                    break;
                default:
                    throw new Exception('Invalid search type');
            }

            $query->andWhere("$rowName like :searchString", [':searchString' => "%{$params['search']}%"]);
        }

        return $dataProvider;
    }
}
