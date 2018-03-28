<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 2016/7/27
 * Time: 11:04
 */
namespace admin\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'],'file','extensions'=>'jpg,png,gif', 'mimeTypes' => 'image/jpeg, image/png, image/gif','maxSize'=>1024000],
        ];
    }
}