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
    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;
    public const STATUS_ERROR = 4;

    public const STATUSES = [
        self::STATUS_PENDING     => 'Pending',
        self::STATUS_IN_PROGRESS => 'In progress',
        self::STATUS_COMPLETED   => 'Completed',
        self::STATUS_CANCELED    => 'Canceled',
        self::STATUS_ERROR       => 'Error',
    ];

    public const MODE_MANUAL = 0;
    public const MODE_AUTO = 1;

    public const MODES = [
        self::MODE_MANUAL => 'Manual',
        self::MODE_AUTO   => 'Auto',
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
