<?php

namespace aquy\seo\module\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

class SeoMeta extends \yii\db\ActiveRecord
{

    const H1 = 'h1';
    const TITLE = 'title';
    const META_KEYWORDS = 'keywords';
    const META_DESCRIPTION = 'description';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_meta}}';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className()
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'name', 'content'], 'required'],
            [['page_id', 'created_at', 'updated_at'], 'integer'],
            ['name', 'in', 'range' => array_keys($this->nameList())],
            ['name', 'unique', 'targetAttribute' => ['page_id', 'name']],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Страница',
            'name' => 'Тип',
            'content' => 'Текст',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    public static function nameList()
    {
        return [
            self::H1 => 'H1',
            self::TITLE => 'Title',
            self::META_KEYWORDS => 'Meta Keywords',
            self::META_DESCRIPTION => 'Meta Description',
        ];
    }

}
