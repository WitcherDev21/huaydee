<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\libs\Constants;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property int $confirmed_at
 * @property string $unconfirmed_email
 * @property int $blocked_at
 * @property string $registration_ip
 * @property int $created_at
 * @property int $updated_at
 * @property int $flags
 * @property int $last_login_at
 * @property string $tel
 * @property int $agent
 * @property int $auth_roles_id
 * @property int $user_status
 *
 * @property Credit[] $credits
 * @property CreditTransection[] $creditTransections
 * @property CreditTransection[] $creditTransections0
 * @property CreditTransection[] $creditTransections1
 * @property CreditTransection[] $creditTransections2
 * @property News[] $news
 * @property News[] $news0
 * @property PostCreditTransection[] $postCreditTransections
 * @property PostCreditTransection[] $postCreditTransections0
 * @property PostCreditTransection[] $postCreditTransections1
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 * @property UserSearch $agent0
 * @property UserSearch[] $userSearches
 * @property AuthRoles $authRoles
 * @property UserHasBank[] $userHasBanks
 * @property UserHasBank[] $userHasBanks0
 * @property UserHasBank[] $userHasBanks1
 * @property UserHasBankLog[] $userHasBankLogs
 * @property Yeekee[] $yeekees
 * @property Yeekee[] $yeekees0
 * @property Yeekee[] $yeekees1
 * @property Yeekee[] $yeekees2
 * @property YeekeeChit[] $yeekeeChits
 */
class UserSearch extends \yii\db\ActiveRecord
{
    public $text_filter;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'auth_key', 'created_at', 'updated_at', 'tel'], 'required'],
            [['confirmed_at', 'blocked_at', 'created_at', 'updated_at', 'flags', 'last_login_at', 'agent', 'auth_roles_id', 'user_status'], 'integer'],
            [['username', 'email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['tel'], 'string', 'max' => 15],
            [['username'], 'unique'],
            [['agent'], 'exist', 'skipOnError' => true, 'targetClass' => UserSearch::className(), 'targetAttribute' => ['agent' => 'id']],
            [['auth_roles_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRoles::className(), 'targetAttribute' => ['auth_roles_id' => 'id']],
            [['text_filter'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'confirmed_at' => 'Confirmed At',
            'unconfirmed_email' => 'Unconfirmed Email',
            'blocked_at' => 'Blocked At',
            'registration_ip' => 'Registration Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'flags' => 'Flags',
            'last_login_at' => 'Last Login At',
            'tel' => 'Tel',
            'agent' => 'Agent',
            'auth_roles_id' => 'Auth Roles ID',
            'user_status' => 'User Status',
            'text_filter' => 'ค้นหาทุกคอลัมน์'
        ];
    }
    
    public function search($params)
    {
    	
    	$query = $this->find()
            ->joinWith(['userHasBanks','credits','yeekeeChits']);
    	
        
//\yii\helpers\VarDumper::dump($query,100,1);
//        exit;
        
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'pagination' => [
						'pageSize' => 20
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_ASC 
						] 
				],
    	]);
    	
    	$this->load($params);
    	if (!$this->validate()) {
            return $dataProvider;
    	}
    
    	return $dataProvider;
    }
    
    public function searchRecommend($params)
    {
    	$this->load($params);
        
        $filter = '';
        if(!empty($this->username)){
            $filter .= 'where u.username LIKE "%'.$this->username.'%" ';
        }
        
        $query = Yii::$app->db->createCommand
        ("
            SELECT
                    u.*,a.username AS agent_name,
                    c.balance AS comm,
                    (SELECT COUNT(id) AS all_agent FROM user WHERE agent=u.id) AS all_agent,
                    (SELECT COUNT(id) AS click FROM log_click WHERE from_user_id=u.id) AS click
            FROM `user` u
            LEFT JOIN `user` a ON a.id=u.agent
			LEFT JOIN `commission` c ON c.user_id = u.id
            $filter
        ")->queryAll();
        
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }

    public function searchRecommendBlackred($params)
    {
        $this->load($params);

        $filter = '';
        if(!empty($this->username)){
            $filter .= 'AND u.username LIKE "%'.$this->username.'%" ';
            $filter .= 'OR a.username LIKE "%'.$this->username.'%" ';
        }

        $query = Yii::$app->db->createCommand
        ("
            SELECT
                    u.*,a.username AS agent_name,
                    c.balance AS comm,
                    (SELECT COUNT(id) AS all_agent FROM ".User::tableName()." WHERE agent=u.id) AS all_agent,
                    (SELECT COUNT(id) AS click FROM ".LogClick::tableName()." WHERE from_user_id=u.id) AS click
            FROM ".User::tableName()." u
            LEFT JOIN ".User::tableName()." a ON a.id=u.agent
			LEFT JOIN ".CommissionBlackred::tableName()." c ON c.user_id = u.id
            WHERE u.id IN (
                    SELECT
                            agent
                    FROM ".User::tableName()."
                    WHERE agent IS NOT NULL
                    GROUP BY agent
            )
            $filter
        ")->queryall();

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }

    public function searchMember($params)
    {
    
    	$this->load($params);
        
        $filter = '';
        if(!empty($this->text_filter)){
            $filter .= 'AND (';
            $filter .= 'u.username LIKE "%'.$this->text_filter.'%" ';
            $filter .= 'OR uhb.bank_account_name LIKE "%'.$this->text_filter.'%" ';
            $filter .= 'OR ba.title LIKE "%'.$this->text_filter.'%" ';
            $filter .= 'OR uhb.bank_account_no LIKE "%'.$this->text_filter.'%" ';
            $filter .= 'OR u.tel LIKE "%'.$this->text_filter.'%" ';
            $filter .= 'OR u.created_at="'.$this->text_filter.'" ';
            $filter .= ') ';
        }
        if($this->user_status !== NULL && $this->user_status !== ''){
            $filter .= 'AND u.user_status="'.$this->user_status.'" ';
        }

        $query = Yii::$app->db->createCommand
        ("
            SELECT 
	
            u.id, u.username, uhb.bank_account_name,ba.title,uhb.bank_account_no, 
            cre_sub.balance,chit.total_amount, thaishared_chit.total_amount_thaishared, 
            blackred_chit.total_amount_blackred, u.tel, u.user_status, u.created_at,
            u.agent,uhb.status

            FROM ".User::tableName()." u

            LEFT JOIN (
            SELECT max(id) AS id, user_id FROM ".UserHasBank::tableName()." GROUP BY user_id
            ) uhb_sub ON uhb_sub.user_id=u.id

            LEFT JOIN user_has_bank uhb ON uhb.id=uhb_sub.id
            LEFT JOIN bank ba ON ba.id=uhb.bank_id

            LEFT JOIN (
            SELECT sum(balance) AS balance, user_id FROM ".Credit::tableName()." GROUP BY user_id
            ) cre_sub ON cre_sub.user_id=u.id

            LEFT JOIN (
            SELECT sum(total_amount) AS total_amount, user_id FROM ".YeekeeChit::tableName()." GROUP BY user_id
            ) chit ON chit.user_id=u.id
            
             LEFT JOIN (
            SELECT sum(totalAmount) AS total_amount_thaishared, userId FROM " . ThaiSharedGameChit::tableName() . " GROUP BY userId
            ) thaishared_chit ON thaishared_chit.userId=u.id
            
            LEFT JOIN ( SELECT sum(total_amount) AS total_amount_blackred, user_id FROM " . BlackredChit::tableName() . " GROUP BY user_id ) 
            blackred_chit ON blackred_chit.user_id=u.id

            WHERE u.auth_roles_id='".Constants::auth_roles_member."'
                $filter
            ORDER BY u.id DESC

        ")->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }
    
    public function searchAssistant($params)
    {
    
    	$this->load($params);
        
        $filter = '';
        if(!empty($this->auth_roles_id)){
            $filter .= 'u.auth_roles_id="'.$this->auth_roles_id.'" ';
        }else{
            $authRoles = AuthRoles::find()->select('id')->where(['is_active' => 1])->asArray()->all();
            $authRolesIds = ArrayHelper::getColumn($authRoles, 'id');
            $filter .= "u.auth_roles_id IN (".implode(',' , $authRolesIds).") ";
        }
        if(!empty($this->username)){
            $filter .= 'AND u.username LIKE "%'.$this->username.'%" ';
        }
        if(!empty($this->user_status)){
            $filter .= 'AND u.user_status="'.$this->user_status.'" ';
        }
        //u.auth_roles_id='".Constants::auth_roles_admin."'
        $query = Yii::$app->db->createCommand
        ("
            SELECT 
            u.id, u.username, u.tel, u.user_status, u.created_at, u.last_login_at
            FROM user u
            WHERE 
                $filter
            ORDER BY u.updated_at DESC
        ")->queryall();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }
    
    public function searchAgent($params)
    {
    
    	$this->load($params);
        
        $filter = '';
        if(!empty($this->username)){
            $filter .= 'AND u.username LIKE "%'.$this->username.'%" ';
        }
        if(!empty($this->user_status)){
            $filter .= 'AND u.user_status="'.$this->user_status.'" ';
        }
        
        $query = Yii::$app->db->createCommand
        ("
            SELECT 
	
            u.id, u.username, uhb.bank_account_name,uhb.bank_account_no, cre_sub.balance,chit.total_amount, u.tel, u.user_status, u.created_at

            FROM user u

            LEFT JOIN (
            SELECT max(id) AS id, user_id FROM user_has_bank GROUP BY user_id
            ) uhb_sub ON uhb_sub.user_id=u.id

            LEFT JOIN user_has_bank uhb ON uhb.id=uhb_sub.id

            LEFT JOIN (
            SELECT sum(balance) AS balance, user_id FROM credit GROUP BY user_id
            ) cre_sub ON cre_sub.user_id=u.id

            LEFT JOIN (
            SELECT sum(total_amount) AS total_amount, user_id FROM yeekee_chit GROUP BY user_id
            ) chit ON chit.user_id=u.id

            WHERE u.auth_roles_id='".Constants::auth_roles_agent."'
                $filter
            ORDER BY u.id ASC

        ")->queryall();
        
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }
    
    public static function getUserProfile($id,$by)
    {
        $query = UserSearch::find()->where([$by=>$id])->one();
        return $query;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredits()
    {
        return $this->hasMany(Credit::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditTransections()
    {
        return $this->hasMany(CreditTransection::className(), ['operator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditTransections0()
    {
        return $this->hasMany(CreditTransection::className(), ['reciver_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditTransections1()
    {
        return $this->hasMany(CreditTransection::className(), ['create_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditTransections2()
    {
        return $this->hasMany(CreditTransection::className(), ['update_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['create_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews0()
    {
        return $this->hasMany(News::className(), ['update_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCreditTransections()
    {
        return $this->hasMany(PostCreditTransection::className(), ['poster_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCreditTransections0()
    {
        return $this->hasMany(PostCreditTransection::className(), ['create_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCreditTransections1()
    {
        return $this->hasMany(PostCreditTransection::className(), ['update_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent0()
    {
        return $this->hasOne(UserSearch::className(), ['id' => 'agent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSearches()
    {
        return $this->hasMany(UserSearch::className(), ['agent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthRoles()
    {
        return $this->hasOne(AuthRoles::className(), ['id' => 'auth_roles_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasBanks()
    {
        return $this->hasMany(UserHasBank::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasBanks0()
    {
        return $this->hasMany(UserHasBank::className(), ['create_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasBanks1()
    {
        return $this->hasMany(UserHasBank::className(), ['update_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasBankLogs()
    {
        return $this->hasMany(UserHasBankLog::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekees()
    {
        return $this->hasMany(Yeekee::className(), ['create_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekees0()
    {
        return $this->hasMany(Yeekee::className(), ['update_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekees1()
    {
        return $this->hasMany(Yeekee::className(), ['user_id_1' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekees2()
    {
        return $this->hasMany(Yeekee::className(), ['user_id_16' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekeeChits()
    {
        return $this->hasMany(YeekeeChit::className(), ['user_id' => 'id']);
    }

}
