<?php

use yii\db\Migration;

/**
 * Class m200512_100844_create_services_and_data
 */
class m200512_100844_create_services_and_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('services', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(300)->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->execute("INSERT INTO services (id, name) VALUES
        (1, 'Likes'),
        (2, 'Followers'),
        (3, 'Views'),
        (4, 'Tweets'),
        (5, 'Retweets'),
        (6, 'Comments'),
        (7, 'Custom comments'),
        (8, 'Page Likes'),
        (9, 'Post Likes'),
        (10, 'Friends'),
        (11, 'SEO'),
        (12, 'Mentions'),
        (13, 'Mentions with Hashtags'),
        (14, 'Mentions Custom List'),
        (15, 'Mentions Hashtag'),
        (16, 'Mentions User Followers'),
        (17, 'Mentions Media Likers');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('services');

        return false;
    }
}
