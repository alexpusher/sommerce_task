<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 *
 * @property Order[] $orders
 */
class Service extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['service_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getServicesWithOrdersCount(): array
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('
        select s.id, s.name, count(o.id) as count_orders 
        from services s
        left join orders o on o.service_id = s.id
        group by s.id, s.name
        order by id, count_orders desc');
        $result = $command->queryAll();

        $services = [];
        foreach ($result as $service) {
            $serviceName = "{$service['count_orders']} {$service['name']}";
            $services[$service['id']] = $serviceName;
        }

        return $services;
    }
}
