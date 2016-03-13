<?php

namespace aquy\seo\module\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "seo_page".
 *
 * @property integer $id
 * @property string $view
 * @property string $action_params
 */
class SeoPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['view'], 'string', 'max' => 150],
            [['action_params'], 'string', 'max' => 600]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'view' => 'View',
            'action_params' => 'Action Params',
        ];
    }

    public function getMeta()
    {
        return $this->hasMany(SeoMeta::className(), ['page_id' => 'id']);
    }

    public function getTitle()
    {
        return $this->hasOne(SeoMeta::className(), ['page_id' => 'id'])->where(['name' => SeoMeta::TITLE]);
    }

    public function getKeywords()
    {
        return $this->hasOne(SeoMeta::className(), ['page_id' => 'id'])->where(['name' => SeoMeta::META_KEYWORDS]);
    }

    public function getDescription()
    {
        return $this->hasOne(SeoMeta::className(), ['page_id' => 'id'])->where(['name' => SeoMeta::META_DESCRIPTION]);
    }

}
