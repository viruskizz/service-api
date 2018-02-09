<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
// use app\modules\v1\models\MainUser;
use app\common\models\User;
use app\common\models\Keychain;
use app\common\models\Client;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Katanyoo Ubalee <ubalee.k@gmai.com>
 */
class ManageServiceController extends Controller
{
    /**
     * Automage Signup from params
     * if(update == false) its only new signup
     * @return void
     */
    public function actionAutomateSignupClient($update = false){
        $lists = Yii::$app->params['clients'];
        foreach($lists as $list){
            $client = Client::find()->where(['username' => $list])->one();
            if(!isset($client)){
                $client = new Client();
                $client->username = $list;
                $client->setPassword($list."123456");
                $client->generateAuthKey();
                $client->generateAccessToken();
                $client->save() ? print_r($client->attributes) : print_r($client->errors); 
            }else if($update){
                $client->setPassword($list."123456");
                $client->generateAuthKey();
                $client->generateAccessToken();
                $client->save() ? print_r($client->attributes) : print_r($client->errors); 
            }
        }
    }
    /**
     * Automate Signup Service-Token
     *
     * @return void
     */
    public function actionAutomateStoreService(){
        $lists = Yii::$app->params['access_token'];
        foreach($lists as $name => $list){
            $service = KeyChain::find()->where(['service_name' => $name])->one();
            if(!$service){
                $service = new KeyChain();
                $service->service_name = $name;
            }
            $service->access_token = $list['token'];
            $service->service_endpoint = $list['endpoint'];
            $service->save() ? print_r($service->attributes) : print_r($service->errors); 
        }
    }

    /**
     * This command for signup new user.
     */
    public function actionSignupClient($username, $password){
        $client = new Client();
        $client->username = $username;
        $client->setPassword($password);
        $client->generateAuthKey();
        $client->generateAccessToken();
        
        $client->save() ? print_r($client->attributes) : print_r($client->errors);
    }

    /**
     *  Add Service from params
     */
    public function actionAddServiceToken($name){
        if(array_key_exists($name, Yii::$app->params['access_token'])){
            if(isset(Yii::$app->params['access_token'][$name]['token'])){
                $token = Yii::$app->params['access_token'][$name]['token'];
            }else{
                print_r("doesn't have access_token ${name} VALUE in api-params");
                return false;
            }
            if(isset(Yii::$app->params['access_token'][$name]['endpoint'])){
                $version = Yii::$app->params['access_token'][$name]['endpoint'];
            }else{
                print_r("doesn't endpoint ${name} value in api-params");
                return false;
            }
        }else{
            print_r("doesn't have access_token ${name} KEY in api-params");
            return false;
        }
        

        $keychain = Keychain::find()->where(['service_name' => $name])->one();
        if(!isset($keychain)){
            $keychain = new Keychain;
            $keychain->service_name = $name;
        }
        $keychain->service_endpoint = Yii::$app->params['access_token'][$name]['endpoint'];
        $keychain->access_token = Yii::$app->params['access_token'][$name]['token'];

        $keychain->save() ? print_r($keychain->attributes) : print_r($keychain->errors);
    }

    public function actionShowKeychain(){
        $keys = Keychain::find()->all();
        echo "No. of Service = ".count($keys)."\n";
        foreach($keys as $key){
            echo "Service :>  ".$key->service_name."\n";
            echo "access_token :>  ".$key->access_token."\n";
            echo "======================================="."\n";
        }
    }

    public function actionShowClient(){
        $clients = Client::find()->all();
        echo "No. of Service = ".count($clients)."\n";
        foreach($clients as $client){
            echo "Client :>  ".$client->username."\n";
            echo "access_token :>  ".$client->access_token."\n";
            echo "======================================="."\n";
        }
    }
}
