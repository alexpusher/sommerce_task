<?php

use yii\db\Migration;

/**
 * Class m200512_100928_create_keys_and_indexes
 */
class m200512_100928_create_keys_and_indexes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-orders-service-id',
            'orders',
            'service_id',
            'services',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-orders-service-id', 'orders');

        return false;
    }
}
