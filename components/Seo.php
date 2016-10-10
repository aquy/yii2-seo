<?php

namespace aquy\seo\components;

use aquy\seo\module\models\SeoMeta;
use aquy\seo\module\models\SeoPage;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\View;
use yii\base\Object;

class Seo extends Object {

    /**
     * @var array дублируем из юрл мэнеджера пути с доступными параметрами
     * остальные запросы с параметрами не будут добавляться в БД.
     * нужно чтобы корректно обрабатывались utm и прочие метки
     *
     * Например,
     *
     * ~~~
     * [
     *   'site/index' => ['myParam', 'mySecondParam'],
     *   'site/about',
     * ]
     * ~~~
     */
    public $actions = [];

    protected $_page;
    protected $_action_params;
    protected $seoBlock = [];
    protected $metaBlock = [];
    protected $meta = [
        'keywords' => 'Meta Keywords',
        'description' => 'Meta Description'
    ];

    protected $block = [
        'title' => 'Page Title',
        'h1' => 'H1'
    ];

    public function init()
    {
        Yii::$app->on(Controller::EVENT_BEFORE_ACTION, [$this, '_meta_page']);
        Yii::$app->on(Controller::EVENT_AFTER_ACTION, [$this, '_meta_init']);
        Yii::$app->view->on(View::EVENT_BEGIN_BODY, [$this, '_link']);
    }

    public function _link()
    {
        if (Yii::$app->user->can('admin') && !is_null($this->_page)) {
            echo Html::tag(
                'div',
                Html::a(
                    'сео',
                    '/backend/seo/meta/update?id=' . $this->_page['id'],
                    ['style' => 'padding: 2px 10px;border-radius:3px;background-color:blue;color:white;']
                ) . ' ' .
                Html::a(
                    'администрирование',
                    '/backend',
                    ['style' => 'padding: 2px 10px;border-radius:3px;background-color:blue;color:white;']
                ),
                ['style' => 'text-align:center;font-size:14px;background-color:white;z-index:9999;position:relative;padding: 3px 10px;']
            );
        }
    }

    public function _meta_page()
    {
        $where['view'] = $this->_view();
        $where['action_params'] = $this->_action_params();
        $this->_page = SeoPage::find()->where($where)->with('meta')->asArray()->one();
        if (!is_null($this->_page) && ($where['view'])) {
            $this->renderMeta();
        }
    }

    public function _meta_init($event)
    {
        if (is_null($this->_page)) {
            $page = new SeoPage();
            $page->view = $this->_view();
            $page->action_params = $this->_action_params();
            $page->save();
            $this->_page = $page;
        }
    }

    protected function renderMeta()
    {
        foreach($this->_page['meta'] as $meta) {
            if (isset($this->meta[$meta['name']])) {
                Yii::$app->view->registerMetaTag([
                    'name' => $meta['name'],
                    'content' => $meta['content']
                ]);
                $this->metaBlock[$meta['name']] = $meta['content'];
            } else if (isset($this->block[$meta['name']])) {
                $this->seoBlock[$meta['name']] = $meta['content'];
            }
        }
    }

    protected function _view()
    {
        $view = Yii::$app->controller->route;
        if (strpos($view, 'debug') !== false || strpos($view, 'error') !== false) {
            return false;
        }
        return $view;
    }

    protected function _action_params()
    {
        $action_params = Yii::$app->request->queryParams;
        if ($this->_view() && isset($this->actions[$this->_view()])) {
            $action_param = $this->actions[$this->_view()];
            foreach($action_params as $key => $value) {
                if (is_null($value) || $value == '' || !ArrayHelper::isIn($key, $action_param)) {
                    unset($action_params[$key]);
                }
            }
        }
        $action_params = Json::encode($action_params);
        return $action_params;
    }

    public function block($name)
    {
        if (ArrayHelper::keyExists($name, $this->seoBlock)) {
            return $this->seoBlock[$name];
        }
        return null;
    }

    public function meta($name)
    {
        if (ArrayHelper::keyExists($name, $this->metaBlock)) {
            return $this->metaBlock[$name];
        }
        return null;
    }

    public function isMeta($name)
    {
        if ($this->_page['meta']) {
            foreach ($this->_page['meta'] as $meta) {
                if ($meta['name'] === $name) {
                    return true;
                }
            }
        }
        return false;
    }

} 