<?php
use Fuel\Core\Log;
use Fuel\Core\DB;

class Controller_Chat extends Controller {
    
    /********************
     *IBM Cloud Lite @andou@parco-city.co.jp
     *REGION:US South, CLOUD FOUNDRY組織:SOFTBANK-ECO-00932, CLOUD FOUNDRY スペース：Space1
     *Conversation-8i
     *キー名:Credentials-1, 作成日:2017年2月17日 - 07:53:00
     ********************/ 
    /*
    private $user_id = "b0d9def7-035c-40fa-bec6-b56408ed4fe1";//Credentials-1
    private $password = "AloZ5oWM2sad";
    private $version = "2017-02-03";
    private $workspaceID = "5cfe92e7-ff44-49eb-8944-b85e621c4f62";//ando study pdm
    */
    /*******************
     *IBM Cloud Lite @andou@parco-digital.co.jp
     *REGION:US South, CLOUD FOUNDRY組織:andou@parco-digital.co.jp, CLOUD FOUNDRY スペース：dev
     *Conversation-5p
     *キー名:conversation_tooling_key1511143864109, 作成日:2017年11月20日 - 11:11:05
     ********************/
    private $user_id = "21b7379c-ff9c-4644-8cba-fc250a517fe8";//Credentials-1
    private $password = "RRdTxSxmXN5T";
    private $version = "2017-02-03";
    private $workspaceID = "872c401e-2e56-42cd-929b-0b25bfd3efc1";//ando study pdm

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
            $this->workspace_id = $this->workspaceID;
        }
    }
    /***********************************
     * $ch 
     *      1:レイアウト1（不採用）
     *      2:レイアウト2（SB_BOT）
     *      3:レイアウト2（PDM）
     ***********************************/
    public function action_index($ch = 2)
    {
        $message = (Input::param("message"))? : "";
        $context = (Input::param("context"))? : "";
        
        $user  = "'" . $this->user_id . "':'" . $this->password . "' ";
        $user_cmd = "-u $user";
        $workspace_id = $this->workspace_id;
        $content_type = "application/json";
        
        $url = "";
        $body_cmd = "";
        $header_cmd = "";
        
        //  /v1/workspaces/{workspace_id}/message
        $url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/$workspace_id/message?version=".$this->version;
        $body = array("input" => array(
                "text" => $message,
                "alternate_intents" => true
        ));

        if($context){
            $context = json_decode($context, true);
            $context = array("context"=>$context);
            $body = array_merge($body, $context);
        }
        $body = json_encode($body, JSON_UNESCAPED_UNICODE);

        $body_cmd = "-X POST -d '$body' ";
        $header_cmd = "--header 'Content-Type: $content_type' --header 'Accept: $content_type' ";
        //$basic_cmd = "--header 'Authorization: Basic YjBkOWRlZjctMDM1Yy00MGZhLWJlYzYtYjU2NDA4ZWQ0ZmUxOkFsb1o1b1dNMnNhZA==' ";

        
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
        
        $data['ch'] = $ch;
        $data['cmd'] = $cmd;
        $data['res'] = $res;
        $data['selapis'] = $this->selapis;
        //$this->template->content = View::forge('apitest',$data);
        
        //メッセージアウトプットテキスト取得
        $output_message = '';
        if(isset($res['output']['text'])){
            foreach($res['output']['text'] as $val){
                $output_message .= $val;
            }
        }
        else{
            echo "None Output";
        }
        $output_messages = $this->messageConvert($output_message);
        //$data['output_message'] = $output_messages["text"];
        //$data['output_message'] = nl2br($output_messages["text"]);
        $data['output_message'] = str_replace("\n","",$output_messages["text"]);
        $data['selectlists'] = $output_messages["selectlists"];
        //Debug::dump($data);exit;
        
        //self::pusherIo($output_message);
        
        if($ch == 1){
            $data['css'] = array(
                    '/chat/common.css',
            );
            $data['js'] = array(
                    '',
            );
            return Response::forge(View::forge('chat/index',$data));
        }
        if($ch == 2 || $ch == 3){
            $data['css'] = array(
                    'bootstrap-3.3.7.min.css',
                    '/chat/common2.css',
            );
            $data['js'] = array(
                    'jquery-3.2.0.min.js',
                    'jquery.bubble.text.js',
            );
            return Response::forge(View::forge('chat/index2',$data));
        }
    }
    
    /*
     * $ch 
     *      1:レイアウト1（不採用）
     *      2:レイアウト2（SB_BOT）
     *      3:レイアウト2（PDM）
     */
    public function action_ajax($ch = 2)
    {
        $message = (Input::param("message"))? : "";
        $context = (Input::param("context"))? : "";
        
        $user  = "'" . $this->user_id . "':'" . $this->password . "' ";
        $user_cmd = "-u $user";
        //$workspace_id = "4a2fa0d8-128f-413f-ad6c-bcfbd90f5587";//SB BOT 演習正解用1 [SB BOT_演習正解用.json]
        $workspace_id = $this->workspace_id;
        $content_type = "application/json";
        Log::debug(__CLASS__.":".__LINE__." workspace_id:" . $workspace_id);
        
        $url = "";
        $body_cmd = "";
        $header_cmd = "";
        $result = array();
        $result['status'] = 'error';
        $result['message'] = '';
        
        //  /v1/workspaces/{workspace_id}/message
        $url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/$workspace_id/message?version=".$this->version;
        $body = array("input" => array(
                "text" => $message,
                "alternate_intents" => true
        ));
        
        if($context){
            $context = json_decode($context, true);
            $conversation_id = $context['conversation_id'];
            $context = array("context"=>$context);
            $body = array_merge($body, $context);
            //echo "<pre>";var_dump($body);echo "</pre>";
        }

        $body = json_encode($body, JSON_UNESCAPED_UNICODE);
        //echo "<pre>";var_dump($body);echo "</pre>";

        $body_cmd = "-X POST -d '$body' ";
        $header_cmd = "--header 'Content-Type: $content_type' --header 'Accept: $content_type' ";
        //$basic_cmd = "--header 'Authorization: Basic YjBkOWRlZjctMDM1Yy00MGZhLWJlYzYtYjU2NDA4ZWQ0ZmUxOkFsb1o1b1dNMnNhZA==' ";
        
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
        //Log::debug(__CLASS__.":".__LINE__. "   ".print_r($res,true));
        
        //$data['context'] = isset($res['context'])? json_encode($res['context']) : "";
        
        $resMsg = "";
        if(isset($res['output']['text'])){
            foreach($res['output']['text'] as $val){
                $val = preg_replace("/\r\n|\r|\n/", "<br>", $val);
                $resMsg .= $val;
            }
        }
        else{
            $resMsg = "<p>None Output</p>";
        }
        $resMsgs = $this->messageConvert($resMsg);
        $resMsg = $resMsgs["text"];
        $selectlists = $resMsgs["selectlists"];
        
        $result['status'] = 'success';
        $result['message'] = $resMsg;
        $result['context'] = isset($res['context'])? json_encode($res['context']) : "";
        $result['selectlists'] = $selectlists;
        //$result['message'] .= $conversation_id;
        //$result['message'] .= $cmd;
        
        $result = json_encode($result);
        //Log::debug(__CLASS__.":".__LINE__. "   ".print_r($result,true));
        return $result;
    }
    
    /*
     * 
     */
    public function messageConvert($output_message)
    {
        $ret = array();
        $selectlists = array();
        $text = "";
        
        //Debug::dump($output_message);
        //preg_match('/(.*)({\'selectlist\':\'.*\'})(.*)/', $output_message, $ms);
        //preg_match('/([\\s\\S]*)({\'selectlist\':.*\'})([\\s\\S]*)/', $output_message, $ms);
        
        //print_r($arr=array("selectlist"=>array("1","2","3","4")));
        //print_r($json = json_encode($arr));
        //print_r(json_decode($json, true));
        
        $preg_word = '([\\s\\S]*)({\"selectlist\":[.*\]})([\\s\\S]*)';
        if(preg_match("/$preg_word/", $output_message, $ms)){
            //Debug::dump($ms);
            for($i=1; $i<count($ms); $i++){
                //Debug::dump($ms[$i]);
                if(preg_match('/^{\"selectlist\":\[.*\]}$/', $ms[$i], $selectlist)){
                    //Debug::dump($selectlist);
                    //$selectlists = explode(";", $selectlist[1]);
                    $selectlists = json_decode($selectlist[0], true);
                    //Debug::dump($selectlists);
                }
                else{
                    $text .= $ms[$i];
                }
            }
        }
        else{
            $text = $output_message;
        }
        //Debug::dump($selectlists['selectlist']);
        if($selectlists) $selectlists = $selectlists['selectlist'];
        $ret = array("text"=>$text, "selectlists"=>$selectlists);
        //Debug::dump($ret);
        
        return $ret;
    }
    
    
    /*
     * プッシャー（WebSocket）
     */
    public function pusherIo($message = '')
    {
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