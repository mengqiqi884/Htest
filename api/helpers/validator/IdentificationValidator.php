<?php

namespace api\helpers\validator;

use yii\validators\Validator;

class IdentificationValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{
		$res = preg_match('/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/',$model->$attribute);
		if(!$res){
			$this->addError($model,$attribute,'身份证号码格式不正确');
		}
	}

}