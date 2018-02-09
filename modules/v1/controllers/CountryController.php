<?php

namespace app\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use app\modules\v1\models\Country;
/**
 * Country Controller API
 *
 * @author Katanyoo Ubalee <ubalee.k@gmail.com>
 */
class CountryController extends ActiveController
{
	/**
	 * Model class
	 * @var string คลาสที่เชื่อมต่อกับ Controller นี้
	 */
	public $modelClass = 'app\modules\v1\models\Country';

	/**
	 * behavior ต่างๆ ของ controller
	 * @return Array behaviors
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => CompositeAuth::className(),
			'authMethods' => [
				HttpBasicAuth::className(),
				HttpBearerAuth::className(),
			],
			'except' => ['example']
		];
		return $behaviors;
	}

	/**
	 * Example action
	 * @return string anytext
	 */
	public function actionExample() {
		return ['Hello !!'];
	}

	/**
	 * Get Country Data
	 */
	public function actionSaveCountry(){
		$post = Yii::$app->request->post();
		if(!isset($post)){
			throw new \yii\web\BadRequestHttpException('Missing arguments');
		}
		$country = new Country;
		if($model->load($post) && $model->save()){
			return ["success" => true, "data" => $model, "message"=> "success get data"];
		}else{
			return ["success" => true, "data" => $model, "message"=> "cannot save country"];
		}
	}
}
