<?php
namespace app\services\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\httpclient\Client;
use app\common\models\Keychain;
/**
 * Login form
 */
class FbwCenter extends Model
{
    /**
     * Attribute for FbwCenter Model
     * Setting for validate attributes
     * @var [type]
     */
    public $firstname;
    public $lastname;
    public $website;
    public $mobile;
    public $gender_id;

    /**
     * rule for validate attributes
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'website', 'mobile'], 'string'],
            [['gender_id'], 'integer'],

            ['firstname', 'required', 'message' => 'firstname cannot be blank'],
        ];
    }
        /**
     * get Keychain data from table
     *
     * @return [mix] data base common\models\Keychain
     */
    public static function getKeychain(){
        $service = Keychain::find()->where(['service_name' => 'fbw_center'])->one();
        return $service;
    }

    /**
     * Get BaseUrl
     * You can set base url seperate by development environment and production environment 
     *
     * @return string
     */
    public static function getBaseUri(){
        if (YII_ENV_DEV) {
            return 'http://localhost/fbw-core/api/web/v1/';
        }
        if(YII_ENV_PROD){
            return 'https://api.forbrighterworld.org/index.php/v1/';
        }
    }

    /**
     * example-json  api service
     * $_POST data encode to JSON
     * Verb POST
     * Suggest Header : application/json  for massive data
     * Security Header : Authorization Beaer Metohd
     * @return void
     */
    public static function exampleJson(){
        $post['FbwCenter'] = [
            "firstname" => "Araiva",
            "lastname" => "viruskizz",
            "website" => "www.forbirghterworld.org",
            "mobile" => "0123456789",
            "gender_id" => 1,
        ];
        // use model load 
        // $model = new FbwCenter;
        // $model->load($post);

        $uri = self::getBaseUri()."tests/example-json";
        $service = self::getKeychain();
        $client = new Client;
        $response = $client->createRequest()
            ->setUrl($uri)
            ->setMethod('post')
            ->addHeaders([ 'Authorization' => 'Bearer '.$service->access_token,'content-type' => 'application/json'])
            ->setContent(Json::encode($post))
            ->send();
        if($response->isOk){
            return Json::decode($response->content);
        }else{
            print_r($response->content);
            return Json::decode($response->content);
        }
    }

    /**
     * example-post  api service
     * api method get
     * @return void
     */
    public static function exampleGet(){
        $uri = self::getBaseUri()."test/example-json";
        $service = self::getKeychain();
        $client = new Client;
        $response = $client->createRequest()
            ->setUrl($uri)
            ->setMethod('post')
            ->setData(['user_id' => 1])
            ->send();
        if($response->isOk){
            return Json::decode($response->content);
        }else{
            return Json::decode($response->content);
        }
    }
}