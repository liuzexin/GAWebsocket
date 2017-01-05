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

    public function run()
    {
        $this->css();
        $this->js();
        return $this->registerWidget('dialog', 'ga-dialog');
    }

    public function js(){
        $js = <<<JS
        $(function(){
            function appendLi(className, value){
                $('#show-list').append('<li class="'+ className +'">'+ value +'</li>');
            }
            var mySocket = new WebSocket('ws://127.0.0.1:2346');
            mySocket.onopen = function (openEvent) {

            };

            mySocket.onmessage = function (messageEvent) {
                appendLi('dialog-other', messageEvent.data);
            };

            mySocket.onerror = function (errorEvent) {
            };

            mySocket.onclose = function (closeEvent) {
            };



            $('#input-area')[0].addEventListener('keydown',function(e){
                if(e.keyCode!=13)
                return;
                if(this.value == '' || this.value.length > 50){
                    e.preventDefault();
                    this.value = '';
                    alert('请输入小于50个字符必须包含内容');
                    return;
                }


                $('#show-list')[0].scrollTop = $('#show-list')[0].scrollHeight;
                appendLi('dialog-self',this.value);
                mySocket.send(this.value);
                this.value = '';
            });
            $('#ga-dialog').dialog({
                'onClose':function(){
                    mySocket.close();
                }
            })
        });
JS;
        $this->getView()->registerJs($js);
    }

    public function css(){
        $this->getView()->registerCssFile('@vendor/websocket/css/websocket-dialog.css');
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