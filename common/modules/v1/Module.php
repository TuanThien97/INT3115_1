<?php

namespace common\modules\v10;

/**
 * v3 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'common\modules\v10\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->modules = [
            'checkin' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\checkin\Module',
            ],
            'checkout' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\checkout\Module',
            ],
            'absence' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\absence\Module',
            ],
            'prescription' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\prescription\Module',
            ],
            'photos' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\photos\Module',
            ],
            'dailyreport' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\dailyreport\Module',
            ],
            'nutrition' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\nutrition\Module',
            ],
            'healthcare' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\healthcare\Module',
            ],
            'news' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\news\Module',
            ],
            'survey' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\survey\Module',
            ],
            'growthreport' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\growthreport\Module',
            ],
            'chat' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\chat\Module',
            ],
            'curriculum' => [
                // you should consider using a shorter namespace here!
                'class' => 'common\modules\v10\modules\curriculum\Module',
            ],
            'diary' => [
                'class' => 'common\modules\v10\modules\diary\Module',
            ],
            'rewards' => [
                'class' => 'common\modules\v10\modules\rewards\Module',
            ],
            'customer' => [
                'class' => 'common\modules\v10\modules\customer\Module',
            ],
            'book' => [
                'class' => 'common\modules\v10\modules\book\Module',
            ],
        ];
    }
}
