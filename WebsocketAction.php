<?php
/**
 * Created by PhpStorm.
 * User: xin
 * Date: 2017/1/5
 * Time: 下午3:49
 */
namespace ga\websocket;
use yii\base\Action;
use Workerman\Worker;

class WebsocketAction extends Action{

    public $ip = '127.0.0.1';
    public $port = '2346';
    public $mode = ['start'];

    public $onConnect;
    public $onMessage;
    public $onClose;

    private $tmp_argv;

    public function beforeRun(){

        if(!empty($this->mode)){
            global $argv;
            $this->tmp_argv = $argv;
            $argv = array_merge([__FILE__], $this->mode);
            return true;
        }
        return false;
    }

    public function run(){

        $ws_worker = new Worker("websocket://$this->ip:$this->port");
        $ws_worker->count = 2;

        $onConnect = $this->onConnect;
        $onMessage = $this->onMessage;
        $onClose = $this->onClose;

        $ws_worker->onConnect = function($connection) use($onConnect){
            if(!empty($onConnect)){
                return $onConnect($connection);
            }
        };

        $ws_worker->onMessage = function($connection, $data) use ($onMessage){
            if(!empty($onMessage)){
                return $onMessage($connection, $data);
            }
        };

        $ws_worker->onClose = function($connection) use ($onClose){
            if(!empty($onClose)){
                return $onClose($connection);
            }
        };

        Worker::runAll();
        return true;
    }

    public function afterRun(){

        global $argv;
        $argv = $this->tmp_argv;
        return true;
    }
}