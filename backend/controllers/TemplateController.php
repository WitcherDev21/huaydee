<?php
namespace backend\controllers;

use common\models\BlackredChitSearch;
use common\models\ThaiSharedAnswerGame;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\ThaiSharedGameChitSearch;
use common\models\User;
use common\models\UserHasBank;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\libs\Constants;
use common\models\UserSearch;
use common\models\YeekeeChitSearch;
use common\models\YeekeeChitDetail;
use common\models\YeekeeSearch;
use common\models\CreditTransectionSearch;
use common\models\PostCreditTransection;
/**
 * Site controller
 */
class TemplateController extends Controller
{
    
    public function chit_list($id)
    {
        $User = UserSearch::find()
            ->where(['id'=>$id])
            ->one();
        if(!empty($User)){
            $modelYeekeeSearch = new YeekeeChitSearch();
            $modelYeekeeSearch->user_id = $User->id;
            $dataProvider = $modelYeekeeSearch->search('');
            return ['../template/chit_list', [
                'dataUser'=>$User,
                'dataProvider'=>$dataProvider
            ]];
        }else{
            return false;
        }
    }

    public function chitBlackred($id)
    {
        $User = UserSearch::find()
            ->where(['id'=>$id])
            ->one();
        if(!empty($User)){
            $modelYeekeeSearch = new BlackredChitSearch();
            $modelYeekeeSearch->user_id = $User->id;
            $dataProvider = $modelYeekeeSearch->search('');
            return ['../template/chit-blackred', [
                'dataUser'=>$User,
                'dataProvider'=>$dataProvider
            ]];
        }else{
            return false;
        }
    }

    public function chitShared($id)
    {
        $userSearch = UserSearch::find()
            ->where(['id'=>$id])
            ->one();
        if(!empty($userSearch)){
            $searchModel = new ThaiSharedGameChitSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->andWhere(['userId' => $userSearch->id]);
            $dataProvider->query->orderBy('createdAt DESC');
            return ['../template/chit-shared', [
                'dataUser' => $userSearch,
                'dataProvider' => $dataProvider
            ]];
        }else{
            return false;
        }
    }
    
    public function chit_detail($id)
    {
        $data = array();
        $ChitDetail = YeekeeChitDetail::find()->Where(['yeekee_chit_id'=>$id])->orderBy(['create_at'=>SORT_DESC,'number'=>SORT_DESC])->all(); 
        $model = YeekeeChitSearch::findOne(['id'=>$id]);
        
        if(!empty($ChitDetail)){
            $amount_total = 0;
            $win_credit = 0;
            $created_by = '';
            foreach ($ChitDetail as $key=>$val){
                $yeekee = YeekeeSearch::findOne(['id'=>$val->yeekeeChit->yeekee->id]);
                $result = $yeekee->getResults($val->play_type_code);
                if(is_array($result)){
                    $result = implode(',', $result);
                }
                $data['items'][$key] = [
                    'title'=>$val->playTypeCode->title,
                    'number'=>$val->number,
                    'amount'=>number_format($val->amount,2),
                    'jackpot_per_unit'=>$val->playTypeCode->jackpot_per_unit,
                    'play_type_code'=>($result ? $result : ''),
                    'win_credit'=>number_format($val->win_credit,2),
                    'status'=>($val->yeekeeChit->status == Constants::status_finish_show_result ? ($val->flag_result == 1 ? '<a href="javascript:;" class="btn btn-xs btn-success">'.'ชนะ'.'</a>' : '<a href="javascript:;" class="btn btn-xs btn-danger">'.'แพ้'.'</a>') : ''),
                ];
                $amount_total = $amount_total + $val->amount;
                $win_credit = $win_credit + $val->win_credit;
                $created_by = $val->create_by;
            }
            $data['amount_total'] = number_format($amount_total,2);
            $data['win_credit'] = number_format($win_credit,2);
            
            $User = UserSearch::find()
                ->where(['id'=>$created_by])
                ->one();
            
            return ['../template/chit_detail', [
                'dataUser'=>$User,
                'YeekeeChit'=>$model,
                'data'=>$data,
            ]];
        }else{
            return false;
        }
    }

    public function chitSharedDetail($id)
    {

        $data = array();
        $ChitDetail = ThaiSharedGameChitDetail::find()->Where(['thaiSharedGameChitId' => $id])->orderBy(['createdAt' => SORT_DESC, 'number' => SORT_DESC])->all();
        $model = ThaiSharedGameChit::findOne(['id' => $id]);

        if(!empty($ChitDetail)){
            $amount_total = 0;
            $win_credit = 0;
            $created_by = '';
            $discount_total = 0;
            foreach ($ChitDetail as $key => $val){
                $textAnswer = '';
                $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $model->thaiSharedGameId, 'playTypeId' => $val->playTypeId])->all();
                foreach ($thaiSharedAnswerGames as $thaiSharedAnswerGame) {
                    $textAnswer .= $thaiSharedAnswerGame->number.'<br>';
                }
                $data['items'][$key] = [
                    'title'=> $val->playType->title,
                    'number'=> $val->number,
                    'amount'=> number_format($val->amount,2),
                    'discount'=> number_format($val->discount,2),
                    'jackpot_per_unit'=> $val->jackPotPerUnit ? $val->jackPotPerUnit : $val->playType->jackpot_per_unit,
                    'textAnswer'=> $textAnswer,
                    'win_credit'=> number_format($val->win_credit,2),
                    'status'=> ($val->thaiSharedGameChit->status == Constants::status_finish_show_result ? ($val->flag_result == 1 ? '<a href="javascript:;" class="btn btn-xs btn-success">'.'ชนะ'.'</a>' : '<a href="javascript:;" class="btn btn-xs btn-danger">'.'แพ้'.'</a>') : ''),
                ];
                $amount_total = $amount_total + $val->amount;
                $discount_total = $discount_total + $val->discount;
                $win_credit = $win_credit + $val->win_credit;
                $created_by = $val->createdBy;
            }
            $data['amount_total'] = number_format($amount_total,2);
            $data['discount_total'] = number_format($discount_total,2);
            $data['win_credit'] = number_format($win_credit,2);
            $User = UserSearch::find()
                ->where(['id' => $created_by])
                ->one();
            return ['../template/thaishared-detail', [
                'dataUser' => $User,
                'thaiSharedGameChit' => $model,
                'data' => $data,
            ]];
        }else{
            return false;
        }
    }

    public function chitLotteryLaoSetDetail($id)
    {
        $data = array();
        $ChitDetail = ThaiSharedGameChitDetail::find()->where([
            'thaiSharedGameChitId' => $id,
            'flag_result' => 1
        ])->groupBy('numberSetLottery')->all();
        $thaiSharedGameChitDetails = ThaiSharedGameChitDetail::find()->where([
            'thaiSharedGameChitId' => $id,
            'flag_result' => 0
        ])->groupBy('numberSetLottery');
        if ($ChitDetail) {
            $chitDetails = ArrayHelper::map($ChitDetail, 'id', 'numberSetLottery');
            $thaiSharedGameChitDetails->andWhere(['NOT IN', 'numberSetLottery', $chitDetails]);
        }
        $ChitDetail = array_merge($ChitDetail, $thaiSharedGameChitDetails->all());
        $model = ThaiSharedGameChit::findOne(['id' => $id]);

        if(!empty($ChitDetail)){
            $amount_total = 0;
            $win_credit = 0;
            $created_by = '';
            foreach ($ChitDetail as $key => $val){
                $textAnswer = '';
                $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['thaiSharedGameId' => $model->thaiSharedGameId, 'playTypeId' => $val->playTypeId])->all();
                foreach ($thaiSharedAnswerGames as $thaiSharedAnswerGame) {
                    $textAnswer .= $thaiSharedAnswerGame->number.'<br>';
                }
                $data['items'][$key] = [
                    'title'=> $val->playType->title,
                    'number'=> $val->numberSetLottery,
                    'amount'=> number_format($val->amount,2),
                    'jackpot_per_unit'=> $val->playType->jackpot_per_unit,
                    'textAnswer'=> $textAnswer,
                    'win_credit'=> number_format($val->win_credit,2),
                    'status'=> ($val->thaiSharedGameChit->status == Constants::status_finish_show_result ? ($val->flag_result == 1 ? '<a href="javascript:;" class="btn btn-xs btn-success">'.'ชนะ'.'</a>' : '<a href="javascript:;" class="btn btn-xs btn-danger">'.'แพ้'.'</a>') : ''),
                    'flag_result' => $val->flag_result,
                ];
                $amount_total = $amount_total + $val->amount;
                $win_credit = $win_credit + $val->win_credit;
                $created_by = $val->createdBy;
            }
            $data['amount_total'] = number_format($amount_total,2);
            $data['win_credit'] = number_format($win_credit,2);
            $User = UserSearch::find()
                ->where(['id' => $created_by])
                ->one();
            return ['../template/lottery-lao-set-detail', [
                'dataUser' => $User,
                'thaiSharedGameChit' => $model,
                'data' => $data,
            ]];
        }else{
            return false;
        }
    }
    
    public function actionGetrealtime()
    {
        $result = array();
        
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            if(!empty($params['param'])){
                if(in_array('NewCreditTransectionMember', $params['param'])){
                    $result['NewCreditTransectionMember'] = $this->PostCreditTransection();
                }
                if(in_array('CreditMaster', $params['param'])){
                    $modelCreditTransection = new CreditTransectionSearch();
                    $result['CreditMaster'] = 'Credit : '.number_format($modelCreditTransection->getCreditMasterBalance(), 2);
                }
                if(in_array('userCountNoActive', $params['param'])) {
                    $result['userCountNoActive'] = User::find()->joinWith('userHasBank')->where([
                        'status' => Constants::status_waitting
                    ])->count();
                }
            }
        }
        
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    public function PostCreditTransection()
    {
        $amount = PostCreditTransection::find()
                ->joinWith('poster')
                ->where(['status'=>Constants::status_waitting,'user.auth_roles_id'=>Constants::auth_roles_member])
                ->count();
        return ($amount > 0 ? $amount : '');
    }
    
    public function actionGetUserActive()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $userCount = User::find()->where(['user_status' => 0])->count();
        return $userCount;
    }
}
