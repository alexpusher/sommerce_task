<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class Order extends ActiveRecord
{
    const STATUSES = [
        0 => 'Pending',
        1 => 'In progress',
        2 => 'Completed',
        3 => 'Canceled',
        4 => 'Error',
    ];

    const MODES = [
        0 => 'Manual',
        1 => 'Auto',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'link', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'required'],
            [['quantity', 'service_id', 'status', 'created_at', 'mode'], 'integer'],
            [['user', 'link'], 'string', 'max' => 300],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::class, 'targetAttribute' => ['service_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user' => Yii::t('app', 'User'),
            'link' => Yii::t('app', 'Link'),
            'quantity' => Yii::t('app', 'Quantity'),
            'service_id' => Yii::t('app', 'Service ID'),
            'status' => Yii::t('app', '0 - Pending, 1 - In progress, 2 - Completed, 3 - Canceled, 4 - Fail'),
            'created_at' => Yii::t('app', 'Created At'),
            'mode' => Yii::t('app', '0 - Manual, 1 - Auto'),
        ];
    }

    /**
     * Gets query for [[Service]].
     *
     * @return ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }
}
