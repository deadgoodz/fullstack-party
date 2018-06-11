<?php

namespace app\components;

use yii\base\Behavior;
use yii\console\Controller;
use yii\helpers\Url;
use yii;

/**
 * Redirects all users to login page if not logged in
 *
 * Class AccessBehavior
 * @package app\components
 * @author  Artem Voitko <r3verser@gmail.com>
 */
class AccessBehavior extends Behavior
{
    /**
     * Subscribe for events
     * @return array
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction'
        ];
    }

    /**
     * On event callback
     */
    public function beforeAction()
    {
        if (Yii::$app->controller->action->id != 'login' && Yii::$app->controller->action->id != 'register') {
            if (\Yii::$app->getUser()->isGuest) {
                \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);

            }
        }


    }
}