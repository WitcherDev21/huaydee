<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\Yeekee;
use common\libs\Constants;
use common\models\YeekeePost;
use common\models\TestConsole;
use common\models\Running;
use common\models\ConfigGenerateGame;

class CronController extends Controller {

    public function actionTest() {
        echo "Test cron job"; // your logic for deleting old post goes here
        exit();
    }
    public function actionWatchyeekee(){
    	
        $model = new TestConsole();
        $model->value = 'test';
        $model->update_at = date('Y-m-d H:i:s');
        $result = $model->save();
        return $result;
       
    }
    
    public function actionGenerateYeekee(){
        $today = date('Y-m-d');
        
        $running = Running::find()->where([
            'game_id'=>Constants::YEEKEE
        ])->one();
        
        $configGame = ConfigGenerateGame::find()->where([
            'game_id'=>Constants::YEEKEE
        ])->one();
       
        
        if(!empty($configGame)&&!empty($running)){
            $result = true;
            $running->running = $running->running + 1;
            $startAt = $today.' '.$configGame->start_time;
            //check duplicate yeekii
            $yeekee = Yeekee::find()->where([
                'start_at'=>$startAt,
                'status'=>Constants::status_active
            ])->one();
            
            if(empty($yeekee)){
                for($i=0; $i<$configGame->amount_of_round; $i++){
                    $round = $i+1;
                    $addFinishTime = $configGame->sec_per_round * $round;
                    $finishAt = date('Y-m-d H:i:s',strtotime($startAt)+$addFinishTime);
                    
                    $yeekee = new Yeekee();
                    $yeekee->group = $running->running;
                    $yeekee->date_at = $today;
                    $yeekee->round = $round;
                    $yeekee->start_at = $startAt;
                    $yeekee->finish_at = $finishAt;
                    $yeekee->status = Constants::status_active;
                    $yeekee->create_at = date('Y-m-d H:i:s');
                    $yeekee->create_by = 1;
                    if(!$yeekee->save()){
                        $result = false;
                        echo 'generate fail';
                        
                    }
                }
                
                $running->update_at = date('Y-m-d H:i:s');
                $running->update_by = 1;
                if(!$running->save()){
                    $result = false;
                }
            }else{
                $result = false;
            }
        }

    }
}