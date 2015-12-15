<?php

namespace aquy\seo\module;

class Meta extends \yii\base\Module
{
    public $controllerNamespace = 'aquy\seo\module\controllers';
    public $defaultRoute = 'meta';
    public function init()
    {
        parent::init();
    }
}