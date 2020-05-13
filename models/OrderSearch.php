<?php

namespace app\models;

use app\widgets\OrderSearchPanel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Query;

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode'], 'safe']
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
        /*$query = (new Query())
            ->select('*')
            ->from('orders');*/
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $params = $params[$this->formName()];

        $query->andFilterWhere(['mode' => $this->mode]);

        if (isset($params['status'], self::STATUSES[$params['status']])) {
            $query->andWhere('status = :status', [':status' => $params['status']]);
        }

        if (isset(
            $params['search'],
            $params['search-type'],
            OrderSearchPanel::SELECT_OPTIONS[$params['search-type']]
        ) && $params['search'] !== '') {
            switch ($params['search-type']) {
                case OrderSearchPanel::SELECT_OPTION_ORDER_ID:
                    $rowName = 'id';
                    break;
                case OrderSearchPanel::SELECT_OPTION_LINK:
                    $rowName = 'link';
                    break;
                case OrderSearchPanel::SELECT_OPTION_USERNAME:
                    $rowName = 'user';
                    break;
                default:
                    throw new Exception('Wrong search type');
            }

            $query->andWhere("$rowName like :searchString", [':searchString' => "%{$params['search']}%"]);
        }

        return $dataProvider;
    }
}
