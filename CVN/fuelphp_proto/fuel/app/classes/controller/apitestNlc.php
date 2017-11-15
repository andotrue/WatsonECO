<?php
class Controller_ApitestNlc extends Controller {

    private $user_id = "69e3294b-e434-4d39-bdc9-bf675be11237";
    private $password = "K3i4hT6hevdc";
    private $version = "";
    private $selapis = array(
            'list' => '一覧取得（GET）',
            'stat' => 'ステータス取得（GET）　classifier_id指定',
            'clsg' => '分類（GET）　メッセージ指定',
            'clsp' => '分類（POST） 検証中　メッセージ指定',
            'trng' => '学習（POST）　file_name & classifier_name 指定',
            'delt' => '削除（GET）　classifier_id指定',
    );
    
    public function action_index()
    {
        $data = array();
        
        $selapi = (Input::param("selapi"))? : "";
        $message = (Input::param("message"))? : "";
        $classifier_id = (Input::param("classifier_id"))? : "12d107x35-nlc-1545";
        $file_name = (Input::param("file_name"))? : "";
        $classifier_name = (Input::param("classifier_name"))? : "";
        
        $message = urlencode($message);
        $trainparm = "";
        $url = "";
        $text = "";
        $del = "";
        if($selapi == "trng"){
            //学習（POST）　file_name指定
            if($file_name){
                $file_dir = 'data/';
                $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers";
                $trainparm  = " -F training_data=@".$file_dir.$file_name;
                $trainparm .= " -F training_metadata='{\"language\":\"ja\",\"name\":\"$classifier_name\"}'";
            }
        }elseif($selapi == "list"){
            //■一覧取得（GET）
            $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers";
            $cmd = "curl $del -u '$this->user_id':'$this->password' $text '$url' $trainparm";
            $res = shell_exec($cmd);
            $res_arr = json_decode($res, true);
            foreach($res_arr['classifiers'] as $key => $classifier){
                $name = $classifier['name'];
                $classifier_id = $classifier['classifier_id'];
                echo $key." : ".$name;echo "<br>";
                echo $classifier_id;echo "<br>";
                Debug::dump($classifier);
                
                $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers/$classifier_id";
                $cmd = "curl $del -u '$this->user_id':'$this->password' $text '$url' $trainparm";
                $res2 = shell_exec($cmd);
                $res_arr2 = json_decode($res2, true);
                Debug::dump($res_arr2);
                
            }
            goto end;
        }elseif($selapi == "stat"){
            //■ステータス取得（GET）　classifier_id指定
            $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers/$classifier_id";
        }elseif($selapi == "clsg"){
            //■分類（GET）
            $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers/$classifier_id/classify?text=$message";
        }elseif($selapi == "clsp"){
            //■分類（POST） 検証中
            $messagep['text'] = $message;
            $messagep = json_encode($messagep);
            $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers/$classifier_id/classify";
            $text = "-X POST --header 'Content-Type: application/json' --header 'Accept: text/html' -d '$messagep'";
        }elseif($selapi == "delt"){
            //■削除（GET）　classifier_id指定
            if($classifier_id){
                $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers/$classifier_id";
                $del = "-X DELETE";
            }
        }
        
        $cmd = "curl $del -u '$this->user_id':'$this->password' $text '$url' $trainparm";

        
        Debug::dump($cmd);
        //2度実行してしますのでコメントアウトしておく
        /*
        if ( !exec($cmd." 2>&1",$array) ) {
            //command失敗を検知して処理したい
            echo "NG";
            echo "<pre>";var_dump($array);echo "</pre>";
        }
        */
        
        $res = shell_exec($cmd);
        $res_arr = json_decode($res, true);
        Debug::dump($res);
        
        end:
        
        $data['cmd'] = $cmd;
        $data['res'] = $res_arr;
        $data['selapis'] = $this->selapis;
        return Response::forge(View::forge('apitestNlc',$data));
        
    }


}