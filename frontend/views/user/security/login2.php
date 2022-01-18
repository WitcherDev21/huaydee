<?php


use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */
$uri = Yii::getAlias('@web');

$this->registerJs(
    
    <<<JS
$.backstretch("$uri/frontend_login/assets/img/backgrounds/1.jpg");
    /*
        Form validation
    */
    $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    $('.login-form').on('submit', function(e) {
    
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function(){
    		if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).addClass('input-error');
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});
    });
    
JS
);?>
<?php 
$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>ล็อคอินเข้าสู่ระบบ</h3>
                            		<p>ป้อนชื่อผู้ใช้และรหัสผ่านเพื่อเข้าสู่ระบบ:</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-lock"></i>
                        		</div>
                            </div>
                            <div class="form-bottom">
			                    <?php $form = ActiveForm::begin([
				                    'id' => 'login-form',
				                    'enableAjaxValidation' => true,
				                    'enableClientValidation' => false,
				                    'validateOnBlur' => false,
				                    'validateOnType' => false,
				                    'validateOnChange' => false,
				                ]) ?>
			                    	
			                        
			                        <?php if ($module->debug): ?>
				                    <?= $form->field($model, 'login', [
				                        'inputOptions' => [
				                            'autofocus' => 'autofocus',
				                            'class' => 'form-control',
				                            'tabindex' => '1']])->dropDownList(LoginForm::loginList());
				                    ?>
				
					                <?php else: ?>
					
					                    <?= $form->field($model, 'login',
					                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
					                    )->label('User');
					                    ?>
					
					                <?php endif ?>
					
					                <?php if ($module->debug): ?>
					                    <div class="alert alert-warning">
					                        <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
					                    </div>
					                <?php else: ?>
					                    <?= $form->field(
					                        $model,
					                        'password',
					                        ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])
					                        ->passwordInput()
					                        ->label(
					                            Yii::t('user', 'Password')
					                            . ($module->enablePasswordRecovery ?
					                                ' (' . Html::a(
					                                    Yii::t('user', 'Forgot password?'),
					                                    ['/user/recovery/request'],
					                                    ['tabindex' => '5']
					                                )
					                                . ')' : '')
					                        ) ?>
					                <?php endif ?>
					                
			                        <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '3']) ?>
			                        <?= Html::submitButton(
					                    Yii::t('user', 'เข้าสู่ระบบ'),
					                    ['class' => 'btn']
					                ) ?>
			                     <?php ActiveForm::end(); ?>
		                    </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
