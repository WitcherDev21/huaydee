<?php
namespace frontend\component;

use yii\helpers\Html;
use yii\widgets\LinkPager;

class CustomPagination extends LinkPager
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
    }

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions              = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        $linkOptions['onclick']   = 'submit_form(' . $page . ')';

        return Html::tag('li', Html::a($label, '#pagination', $linkOptions), $options);
    }
}
?>