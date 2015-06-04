<?php

use yii\db\Schema;
use yii\db\Migration;

class m150604_223630_init_database extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => 'VARCHAR (100) NOT NULL',
            'password' => 'VARCHAR (250) NOT NULL',
            'auth_key' => 'VARCHAR (250)',
            'access_token' => 'VARCHAR (250)',
        ]);
        
        $this->createIndex('username', 'user', 'username', true);
        $this->createIndex('auth_key', 'user', 'auth_key', true);
        
        $this->createTable('like', [
            'user_id' => 'INTEGER (10) NOT NULL',
            'object_type' => 'INTEGER (1) NOT NULL',
            'object_id' => 'VARCHAR (250)',
        ]);
        
        $this->createIndex('like', 'like', ['user_id','object_type','object_id'], true);
    }

    public function down()
    {
        $this->dropTable('user');
        $this->dropTable('like');
    }
}
