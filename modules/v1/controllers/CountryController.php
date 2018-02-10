<?php

namespace app\modules\v1\controllers;

use Yii;
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
			'except' => ['example', 'save-country']
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
	 * Before use new action please update url rule in file: "config/url" 
	 */
	public function actionSaveCountry(){
		$post = Yii::$app->request->post();
		if(!isset($post['name'])){
			throw new \yii\web\BadRequestHttpException('Missing arguments');
		}
		$model = new Country;
		$model->load($post);
		if($model->save()){
			return ["success" => true, "data" => $model, "message"=> "success get data"];
		}else{
			// return ["success" => false, "data" => $model, "message"=> "cannot save country"];
			$message = implode(", ",$model->firstErrors);
			return ["success" => false, "data" => $model, "message"=> $message];
		}
	}
}
