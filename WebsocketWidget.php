<?php
/**
 * Created by PhpStorm.
 * User: xin
 * Date: 2017/1/5
 * Time: 上午9:29
 */
namespace ga\websocket;
use yii\jui\Widget;
use app\assets\WebsocketAsset;

class WebsocketWidget extends Widget{

    public $listId = 'show-list';
    public $inputId = 'input-area';

    private $parentId = 'ga-dialog';

    public $ip = '127.0.0.1';
    public $port = '2346';
    public function run()
    {
        $this->css();
        $this->js();
        echo $this->renderFile( '@vendor/ga/websocket/asset/dialog.php',[
            'id' => $this->id,
            'listId' => $this->listId,
            'inputId' => $this->inputId,
            'parentId' => $this->parentId
        ]);
        return $this->registerWidget('dialog', $this->parentId);
    }

    public function js(){
        $js = <<<JS

            function appendLi(className, value){
                $('#{$this->listId}').append('<li class="'+ className +'">'+ value +'</li>');
            }
            var mySocket = new WebSocket('ws://{$this->ip}:{$this->port}');
            jQuery('#{$this->parentId}').on('dialogclose', function(){mySocket.close();});
            mySocket.onopen = function (openEvent) {

            };

            mySocket.onmessage = function (messageEvent) {
                appendLi('dialog-other', messageEvent.data);
            };

            mySocket.onerror = function (errorEvent) {
            };

            mySocket.onclose = function (closeEvent) {
            };



            $('#{$this->inputId}')[0].addEventListener('keydown',function(e){
                if(e.keyCode!=13)
                return;
                if(this.value == '' || this.value.length > 50){
                    e.preventDefault();
                    this.value = '';
                    alert('请输入小于50个字符必须包含内容');
                    return;
                }


                $('#{$this->listId}')[0].scrollTop = $('#{$this->listId}')[0].scrollHeight;
                appendLi('dialog-self',this.value);
                mySocket.send(this.value);
                this.value = '';
            });
JS;
        $this->getView()->registerJs($js);
    }

    public function css(){
        $this->getView()->registerCssFile('@vendor/asset/websocket-dialog.css');
    }

    protected function registerWidget($name, $id = null)
    {
        if ($id === null) {
            $id = $this->options['id'];
        }
        WebsocketAsset::register($this->getView());
        $this->registerClientEvents($name, $id);
        $this->registerClientOptions($name, $id);
    }
}