<?php
/**
 * Created by PhpStorm.
 * User: xin
 * Date: 2017/1/5
 * Time: 上午11:49
 */
namespace app\commands;
use yii\console\Controller;
use Workerman\Worker;
class WebsocketServerController extends Controller{

    public $ip = '127.0.0.1';
    public $port = '2346';

    public function actionStart(){
        $ws_worker = new Worker("websocket://$this->ip:$this->port");
        $ws_worker->count = 2;
        $ws_worker->onConnect = function($connection) {
            $this->onConnect();
            echo "New connection\n";
        };

        $ws_worker->onMessage = function($connection, $data)
        {
            $this->onMessage();
            $connection->send('hello ' . $data);
        };

        $ws_worker->onClose = function($connection) {
            $this->onClose();
            echo "Connection closed\n";
        };
        $this->runAll(['start']);
    }

    public function actionStop(){

        $this->runAll(['stop']);
    }
    public function actionStartDaemon(){

        $this->runAll(['start', '-d']);
    }

    public function actionRestart(){
        $this->runAll(['start', '-d']);
    }
    private function onMessage(){

    }

    private function onConnect(){

    }

    private function onClose(){

    }

    private function runAll(array $arg){
        global $argv;
        $tmp_argv = $argv;
        $argv = array_merge([__FILE__], $arg);
        Worker::runAll();
        $argv = $tmp_argv;
    }
}