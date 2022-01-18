<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "games".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $rule
 * @property string $uri
 * @property string $period_des
 * @property int $status
 * @property string $create_at
 * @property string $sort
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property string $gameAuthKey
 * @property string $image
 */
class Games extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%games}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'uri', 'status', 'sort'], 'required'],
            [['id', 'status', 'create_by', 'update_by', 'sort'], 'integer'],
            [['rule', 'gameAuthKey'], 'string'],
            [['create_at', 'update_at'], 'safe'],
            [['title', 'description', 'uri', 'period_des'], 'string', 'max' => 255],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg', 'maxSize' => 200000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'rule' => 'Rule',
            'uri' => 'Url',
            'period_des' => 'Period Des',
            'status' => 'Status',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'gameAuthKey' => 'Game Auth Key',
            'sort' => 'Sort',
            'image' => 'Image',
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
        return Yii::$app->basePath.'/web/uploads/games/';
    }

    public function getUploadUrl()
    {
        return Yii::getAlias('@gameImage');
    }

    public function getPhotoViewer()
    {
        return empty($this->image) ? '' : $this->getUploadUrl().'/'.$this->image;
    }

    public function DeleteFile($attribute)
    {
        return @unlink($this->getUploadPath().$this->$attribute);
    }

    public function getUrlGame()
    {
        return [
            'yeekee/index' => 'ยี่กี่',
            'blackred/index' => 'ดำแดง',
            'thai-shared-chit/index' => 'หวยหุ้น',
            '#' => 'ไม่ระบุ',
        ];
    }

}
