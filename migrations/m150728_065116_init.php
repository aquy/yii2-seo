<?php

use yii\db\Schema;
use yii\db\Migration;

class m150728_065116_init extends Migration
{
    public function up()
    {

        $this->createTable('{{%seo_page}}',[
            'id'             => $this->bigPrimaryKey(),
            'view'           => $this->string(150),
            'action_params'  => $this->string(600),
        ]);

        $this->createTable('{{%seo_meta}}',[
            'id'            => $this->bigPrimaryKey(),
            'page_id'       => $this->bigInteger(),
            'name'          => $this->string(50),
            'content'       => $this->text(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer()
        ]);

    }
    public function down()
    {
        $this->dropTable('{{%seo_page}}');
        $this->dropTable('{{%seo_meta}}');
    }

}
