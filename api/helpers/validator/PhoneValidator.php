<?php

namespace api\helpers\validator;

use yii\validators\Validator;

class PhoneValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{
		$res = preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9][0-9]{8}|17[0-9]{9}$/",$model->$attribute) && strlen($model->$attribute)==11;
		if(!$res){
			$this->addError($model,$attribute,'手机号格式不正确');
		}
	}

}