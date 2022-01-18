<?php
/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/20/2019
 * Time: 10:31 PM
 */
if (YII_ENV === 'prod') {
    Yii::setAlias('@gameImage', 'https://www.myapplication2018.com/version6/backend/web/uploads/games/');
    Yii::setAlias('@pusher', 'https://www.myapplication2018.com/version6/backend/web/uploads/pusher/');
}else{
    Yii::setAlias('@gameImage', 'http://'.$_SERVER['SERVER_NAME'].'/backend/web/uploads/games/');
    Yii::setAlias('@pusher', 'http://'.$_SERVER['SERVER_NAME'].'/backend/web/uploads/pusher/');
}