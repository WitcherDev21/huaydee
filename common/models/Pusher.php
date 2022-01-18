<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "pusher".
 *
 * @property int $id
 * @property string $title
 * @property string $message
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $url
 * @property string $image
 */
class Pusher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pusher}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'time'], 'required'],
            [['createdAt', 'updatedAt', 'image'], 'safe'],
            [['createdBy', 'updatedBy', 'time'], 'integer'],
            [['url'], 'required', 'on' => 'image'],
            [['message'], 'required', 'on' => 'message'],
            [['title', 'message', 'url', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public function scenarios()
    {
        $scenario = parent::scenarios();
        $scenario['image'] = array_merge($scenario['default'], ['url']);
        $scenario['message'] =  array_merge($scenario['default'], ['message']);
        return $scenario;
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'message' => 'Message',
            'time' => 'Time Second',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'url' => 'Url',
            'Image' => 'Image',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function upload($model,$attribute)
    {
        $photo  = UploadedFile::getInstance($model, $attribute);
        $path = $this->getUploadPath();
        if ($this->validate() && $photo !== null) {

            $fileName = md5($photo->baseName.time()) . '.' . $photo->extension;
            if($photo->saveAs($path.$fileName)){
                return $fileName;
            }
        }
        return $model->isNewRecord ? false : $model->getOldAttribute($attribute);
    }

    public function getUploadPath()
    {
        return Yii::$app->basePath.'/web/uploads/pusher/';
    }

    public function getUploadUrl()
    {
        return Yii::getAlias('@pusher');
    }

    public function getPhotoViewer()
    {
        return empty($this->image) ? '' : $this->getUploadUrl().'/'.$this->image;
    }

    public function DeleteFile($attribute)
    {
        return @unlink($this->getUploadPath().$this->$attribute);
    }
}
