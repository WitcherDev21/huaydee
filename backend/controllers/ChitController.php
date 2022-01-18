<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\libs\Constants;
use common\models\YeekeeSearch;
use common\models\YeekeeChitSearch;
use common\models\AuthRoles;
use common\models\YeekeeChit;
use common\models\YeekeeChitDetail;
use common\models\Credit;
use common\models\Yeekee;
use common\models\Running;
use yii\web\NotFoundHttpException;
        
/**
 * Site controller
 */
class ChitController extends Controller
{
    public $defaultAction = 'list';
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('chit', $arrRoles)){
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access'=> [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionList()
    {
        $yeekee = YeekeeSearch::find()->select(['date_at'])->where(['status' => Constants::status_active])->orderBy(['date_at' => SORT_DESC])->one();
        if(empty($yeekee)){
            $yeekee = new YeekeeSearch();
        }
        $searchModel  = new YeekeeChitSearch();
        $searchModel->date_at = $yeekee->date_at;
        $searchModel->page = YeekeeChitSearch::CURRENT;
        if (isset(Yii::$app->request->queryParams['YeekeeChitSearch']['round'])) {
            $searchModel->round = Yii::$app->request->queryParams['YeekeeChitSearch']['round'];
        }
        if (isset(Yii::$app->request->queryParams['YeekeeChitSearch']['date_at'])) {
            $searchModel->date_at = Yii::$app->request->queryParams['YeekeeChitSearch']['date_at'];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel  = new YeekeeChitSearch();
        $searchModel->round = '';
        if (isset(Yii::$app->request->queryParams['YeekeeChitSearch']['round'])) {
            $searchModel->round = Yii::$app->request->queryParams['YeekeeChitSearch']['round'];
        }
        if (isset(Yii::$app->request->queryParams['YeekeeChitSearch']['date_at'])) {
            $searchModel->date_at = Yii::$app->request->queryParams['YeekeeChitSearch']['date_at'];
        }
        $dataProvider2 = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('list',[
            'dataProvider'=>$dataProvider,
            'dataProvider2'=>$dataProvider2,
            'searchModel'=>$searchModel,
            'tab_active'=>(!empty($_GET['id']) ? $_GET['id'] : 'tab1')
        ]);
    }
    
    
    public function actionChit()
    {
    	$params = \Yii::$app->request->get();
    	if(!empty($params) && !empty($params['id'])){
            $obj = TemplateController::chit_list($params['id']);
            if(!empty($obj)){
                return $this->render($obj[0],$obj[1]);
            }
        }
        return $this->redirect(['chit/list']);
    }
    
    public function actionChit_detail()
    {
    	$params = \Yii::$app->request->get();
    	if(!empty($params) && !empty($params['id'])){
            $obj = TemplateController::chit_detail($params['id']);
            if(!empty($obj)){
                return $this->render($obj[0],$obj[1]);
            }
        }
        return $this->redirect(['chit/list']);
    }
    
//    public function actionChitgetdate()
//    {
//        $data = array();
//        $ChitDetail = YeekeeChitDetail::find()->Where(['yeekee_chit_id'=>$_POST['id']])->all(); 
//        $model = YeekeeChitSearch::findOne(['id'=>$_POST['id']]);
//        if(!empty($ChitDetail)){
//            $amount_total = 0;
//            $win_credit = 0;
//            foreach ($ChitDetail as $key=>$val){
//                $yeekee = YeekeeSearch::findOne(['id'=>$val->yeekeeChit->yeekee->id]);
//                $result = $yeekee->getResults($val->play_type_code);
//                if(is_array($result)){
//                    $result = implode(',', $result);
//                }
//                $data[$key] = [
//                    'title'=>$val->playTypeCode->title,
//                    'number'=>$val->number,
//                    'amount'=>number_format($val->amount,2),
//                    'jackpot_per_unit'=>$val->playTypeCode->jackpot_per_unit,
//                    'play_type_code'=>($result ? $result : ''),
//                    'win_credit'=>number_format($val->win_credit,2),
//                    'status'=>($val->yeekeeChit->status == Constants::status_finish_show_result ? ($val->flag_result == 1 ? '<a href="javascript:;" class="btn btn-xs btn-success">'.'ชนะ'.'</a>' : '<a href="javascript:;" class="btn btn-xs btn-danger">'.'แพ้'.'</a>') : ''),
//                ];
//                $amount_total = $amount_total + $val->amount;
//                $win_credit = $win_credit + $model->getTotalWinCredit();
//            }
//            $data['amount_total'] = number_format($amount_total,2);
//            $data['win_credit'] = number_format($win_credit,2);
//        }
//        echo json_encode($data,JSON_UNESCAPED_UNICODE);
//    }
    
}
