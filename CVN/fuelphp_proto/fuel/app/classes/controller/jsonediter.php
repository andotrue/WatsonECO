<?php
class Controller_jsonediter extends Controller {
    public function action_index(){
        $file_content = File::read(DOCROOT.'data/SB BOT_演習正解用.json', true);
        $file_content = json_decode($file_content, true);
        //Debug::dump($file_content);
        
        self::create_conversation_intent($file_content);
    }
    
    public function create_conversation_intent($file_content){
        $data = $file_content['intents'];
        //Debug::dump($data);
        
        $pickup_intents = array(
                            "はい",
                            "いいえ",
                            "ヘルプ",
                            "あいさつ",
                            "不明",
                            "怒り",
                            "雑談",
        ); 
        foreach($data as $val){
            $intent = $val["intent"];
            $examples = $val["examples"];
            if(in_array($intent,$pickup_intents)){
                //Debug::dump($examples);
                foreach($examples as $example){
                    $outdata[$intent][] = $example['text'];
                }
            }
        }
        
        //Debug::dump($outdata);
        
        $str = "";
        $now = date("Ymd");
        $csv_name = "intents_".$now;
        foreach($outdata as $key => $data){
            foreach($data as $datum){
                $str .= $datum . "," . $key . "\r\n";
            }
        }
        // content-type: csv
        header("Content-Type: application/octet-stream");
        // ファイル名をセット
        header("Content-disposition: attachment; filename=".$csv_name.".csv");
        // キャッシュをなしに
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Pragma: no-cache');
        //$str = mb_convert_encoding($str, 'SJIS-win', 'UTF-8');
        echo $str;
        exit;
    }
}