<?php
use Fuel\Core\Log;
use Fuel\Core\DB;

class Controller_ApitestW2c extends Controller {
    
    private $user_id = "w2cuser";
    private $password = "w2cuser";
    private $version = "";
    
    private $selapis = array(
            'clr' => 'clear',
    );
    
    public function before()
    {
        parent::before();
    }
    
    public function action_index(){
        $selapi = (Input::param("selapi"))? : "clr";
        $id = (Input::param("id"))? : "";
        $sid = (Input::param("sid"))? : "";
        
        $user  = "'" . $this->user_id . "':'" . $this->password . "' ";
        $user_cmd = "-u $user";
        $content_type = "application/json";
        
        $url = "";
        $body_cmd = "";
        $header_cmd = "";

        $url = "https://icti-dev.jp/w2c_classifier-g2-3/api/webchat";
        if($id && $sid){
            $choice_id = $id;
            $body = array(
                    "api_version" => "",
                    "session_id" => $sid,
                    "choice_id" => $choice_id,
                    "message" => "",
            );
            $body = json_encode($body, JSON_UNESCAPED_UNICODE);
            
            $body_cmd = "-X POST -d '$body' ";
            $header_cmd = "--header 'Content-Type: $content_type; charset=UTF-8' ";
            $cmd = "curl $user_cmd $header_cmd '$url' $body_cmd";
            $res = $this->callexec($cmd);

            $session_id = $res[1]['session_id'];
        }else{
            $body = array(
                    "api_version" => "",
                    "session_id" => "",
                    "choice_id" => "",
                    "message" => "",
            );
            $body = json_encode($body, JSON_UNESCAPED_UNICODE);
    
            $body_cmd = "-X POST -d '$body' ";
            $header_cmd = "--header 'Content-Type: $content_type; charset=UTF-8' ";
            $cmd = "curl $user_cmd $header_cmd '$url' $body_cmd";
            $res = $this->callexec($cmd);
    
            $session_id = $res[1]['session_id'];
        }
        
        
        $data['cmd'] = $cmd;
        $data['res'] = $res[1];
        $data['selapis'] = $this->selapis;
        $data['session_id'] = $session_id;
        //$data['selapis'] = array();
        return Response::forge(View::forge('apitestW2c',$data));
    }
    
    public function callexec($cmd)
    {
        $res = exec($cmd);
        $res_arr = json_decode($res, true);
        
        return array($res, $res_arr);
    }
    
}

/*
 * curl option
 *  -u USER[:PASS]	認証に用いるユーザー名USER, パスワードPASSを指定する（基本認証など）
 *  -X POST             POSTリクエストを送る
 *  -H --header        HTTP ヘッダを指定する。ユーザエージェントとかリファラとか
 *  -d, --data PARAM...	POSTリクエストとしてフォームを送信する。パラメータPARAMは「"value=name"」の形式で指定する
 */
