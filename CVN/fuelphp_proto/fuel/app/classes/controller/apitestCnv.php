<?php
use Fuel\Core\Log;
use Fuel\Core\DB;

class Controller_ApitestCnv extends Controller {
    
    private $user_id = "b0d9def7-035c-40fa-bec6-b56408ed4fe1";
    private $password = "AloZ5oWM2sad";
    private $version = "2017-02-03";
    
    private $selapis = array(
            'aws' => 'all workspace',
            'ows' => 'one workspace',
            'msg' => 'message',
            'ctex' => 'counterexamples',
    );
    
    public function before()
    {
        parent::before();
        //Debug::dump(Request::main()->method_params);
        $method_params = Request::main()->method_params;
        $method_param0 = isset($method_params[0])? $method_params[0] : "2";
        if($method_param0 == "2"){
            $this->workspace_id = "4a2fa0d8-128f-413f-ad6c-bcfbd90f5587";//SB BOT 演習正解用1 [SB BOT_演習正解用.json]
        }
        elseif($method_param0 == "3"){
            $this->workspace_id = "5cfe92e7-ff44-49eb-8944-b85e621c4f62";//ando study pdm
        }
    }
    
    public function action_index($ch = 2){
        $selapi = (Input::param("selapi"))? : "";
        $message = (Input::param("message"))? : "";
        $context = (Input::param("context"))? : "";
        //echo "/".$context."/";
        
        $user  = "'" . $this->user_id . "':'" . $this->password . "' ";
        $user_cmd = "-u $user";
        $workspace_id = $this->workspace_id;
        $content_type = "application/json";
        
        Debug::dump($workspace_id);
        
        $url = "";
        $body_cmd = "";
        $header_cmd = "";
        if($selapi == "aws"){
            //  /v1/workspaces/
            $url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces?version=".$this->version;
        }
        elseif($selapi == "ows"){
            //  /v1/workspaces/{workspace_id}
            $url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/$workspace_id?version=".$this->version;
        }
        elseif($selapi == "msg"){
            //  /v1/workspaces/{workspace_id}/message
            $url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/$workspace_id/message?version=".$this->version;
            $body = array("input" => array(
                    "text" => $message,
                    "alternate_intents" => true
            ));
            //echo "<pre>";var_dump(json_encode($body, JSON_UNESCAPED_UNICODE));echo "</pre>";
            //$body = "{\"input\":{\"text\": \"$message\"},\"alternate_intents\":true}";
            //echo "<pre>";var_dump($body);echo "</pre>";
            
            //echo "<pre>";var_dump(json_decode($context,true));"</pre>";
            if($context){
                $context = json_decode($context, true);
                $context = array("context"=>$context);
                $body = array_merge($body, $context);
                //echo "<pre>";var_dump($body);echo "</pre>";
            }
 
            $body = json_encode($body, JSON_UNESCAPED_UNICODE);
            //echo "<pre>";var_dump($body);echo "</pre>";

            $body_cmd = "-X POST -d '$body' ";
            $header_cmd = "--header 'Content-Type: $content_type' --header 'Accept: $content_type' ";
            //$basic_cmd = "--header 'Authorization: Basic YjBkOWRlZjctMDM1Yy00MGZhLWJlYzYtYjU2NDA4ZWQ0ZmUxOkFsb1o1b1dNMnNhZA==' ";
        }
        elseif($selapi == "ctex"){
            //  /v1/workspaces/{workspace_id}/counterexamples
            $url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/$workspace_id/counterexamples?version=".$this->version;
        }
        
        /*
         * curl option
         *  -u USER[:PASS]	認証に用いるユーザー名USER, パスワードPASSを指定する（基本認証など）
         *  -X POST             POSTリクエストを送る
         *  -H --header        HTTP ヘッダを指定する。ユーザエージェントとかリファラとか
         *  -d, --data PARAM...	POSTリクエストとしてフォームを送信する。パラメータPARAMは「"value=name"」の形式で指定する
         */
        $cmd = "curl $user_cmd $header_cmd '$url' $body_cmd";
        
        $res = exec($cmd);
        $res = json_decode($res, true);
        
        $data['context'] = isset($res['context'])? json_encode($res['context']) : "";
        
        $data['cmd'] = $cmd;
        $data['res'] = $res;
        $data['ch'] = $ch;
        $data['selapis'] = $this->selapis;
        //$this->template->content = View::forge('apitestCnv',$data);
        
        //メッセージアウトプットテキスト取得
        $message = '';
        if(isset($res['output']['text'])){
            foreach($res['output']['text'] as $val){
                $message .= $val;
            }
        }
        else{
            echo "None Output";
        }
        /*
         * Pusher連動
         * http://lc.cnv_proto/pushertestfront.php
         */
        //self::pusherIo($message);
        return Response::forge(View::forge('apitestCnv',$data));
    }
    
    /*
     * プッシャー（WebSocket）
     */
    public function pusherIo($message = ''){
        //echo VENDORPATH;
        //require('Pusher.php');
        require(VENDORPATH.'pusher/pusher-php-server/lib/Pusher.php');
    
        $options = array(
                'encrypted' => true
        );
        $pusher = new Pusher(
                'af84690ae32132ce6058',
                '8c3a92021a6be2080a08',
                '313964',
                $options
        );
    
        $data['message'] = $message;
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}