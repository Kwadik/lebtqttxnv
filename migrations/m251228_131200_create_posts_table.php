<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m251228_131200_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('{{%posts}}', [
			'id' => $this->primaryKey(),
			'content' => $this->text()->notNull(),
			'author_name' => $this->string(15)->notNull(),
			'author_email' => $this->string(255)->notNull(),
			'author_ip' => $this->string(45)->notNull(),
			'image_path' => $this->string(255),
			'email_token' => $this->string(45)->notNull(),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer(),
			'deleted_at' => $this->integer()->null(),
		]);

		// Индексы
		$this->createIndex('idx-post-author_email', '{{%posts}}', 'author_email');
		$this->createIndex('idx-post-created_at', '{{%posts}}', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%posts}}');
    }
}
