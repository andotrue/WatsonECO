<?php
use Fuel\Core\Log;
use Fuel\Core\DB;

class Controller_ApitestDoc extends Controller {
    
    private $user_id = "01a6ada4-1566-419b-a86e-13315e4a9067";
    private $password = "hIkWKYD1PAXx";
    private $version = "2015-12-15";
    
    private $selapis = array(
            'cdoc' => 'convert_document',
            'idoc' => 'index_document',
    );
    
    public function action_index(){
        $selapi = (Input::param("selapi"))? : "";
        $message = (Input::param("message"))? : "";
        $context = (Input::param("context"))? : "";
        //echo "/".$context."/";
        
        $user  = "'" . $this->user_id . "':'" . $this->password . "' ";
        $user_cmd = "-u $user";
        $content_type = "multipart/form-data";
        $content_type_accept = "application/json";
        //$content_type_accept = "text/html";
        $content_type2 = "application/json";
        
        $url = "";
        $body_cmd = "";
        $header_cmd = "";
        
        $selapi = "cdoc";
        if($selapi == "cdoc"){
            $url = "https://watson-api-explorer.mybluemix.net/document-conversion/api/v1/convert_document?version=".$this->version;
            /*
            config='
            {
                "conversion_target":"answer_units",
                "normalized_html":{"exclude_tags_completely":["script","sup","link"],
                "exclude_tags_keep_content":["font","em","span","strong","code"],
                "keep_content":{"xpaths":["\/\/section[@class=\"columns large-11 body\"]"]},
                "exclude_content":{"xpaths":["\/\/div[@class=\"footer-nav\"]","\/\/section[@class=\"bodySection\"]\/h3","\/body\/div\/section\/section\/p[2]"]},
                "exclude_tag_attributes":["EVENT_ACTIONS","class"]}
            }
            '
            */
            
            $config = array(
                        "conversion_target" => "answer_units",
                        //"conversion_target" => "NORMALIZED_TEXT",
                    
                        //level : 1 (h1), 2 (h2), 3 (h3), 4 (h4), 5 (h5), and 6 (h6).
                        "pdf" => array(
                            "heading"=>
                                array("fonts"=>
                                    array(
                                        array("level"=>1,"min_size"=>14),
                                        array("level"=>2,"min_size"=>11.5, "max_size"=>14),
                                        array("level"=>3,"min_size"=>10.5, "max_size"=>11.5),
                                        /*
                                        array("level"=>1,"min_size"=>24),
                                        array("level"=>2,"min_size"=>18, "max_size"=>23, "bold"=>true),
                                        array("level"=>3,"min_size"=>14, "max_size"=>17, "italic"=>false),
                                        array("level"=>4,"min_size"=>12, "max_size"=>13, "name"=>"Times New Roman"),
                                        */
                                        /*
                                        array("level"=>1,"min_size"=>24, "max_size"=>80),
                                        array("level"=>2,"min_size"=>18, "max_size"=>24, "bold"=>false, "italic"=>false),
                                        array("level"=>2,"min_size"=>18, "max_size"=>24, "bold"=>true),
                                        array("level"=>3,"min_size"=>13, "max_size"=>18, "bold"=>false, "italic"=>false),
                                        array("level"=>3,"min_size"=>13, "max_size"=>18, "bold"=>true),
                                        array("level"=>4,"min_size"=>11, "max_size"=>13, "bold"=>true, "italic"=>false),
                                        */
                                    ),
                                ),
                            ),
                    /*
                        "normalized_html"=>array(
                                "exclude_tags_completely"=>array('script','sup','link'),
                                "exclude_tags_keep_content"=>array('font','em','span','strong','code'),
                                "keep_content"=>array(
                                                    'xpaths'=>array(
                                                            "//section[@class=\"columns large-11 body\"]"
                                                    )
                                ),
                                "exclude_content"=>array(
                                                    'xpaths'=>array(
                                                            "//div[@class=\"footer-nav\"]",
                                                            "//section[@class=\"bodySection\"]/h3",
                                                            "/body/div/section/section/p[2]"
                                                    )
                                ),
                                "exclude_tag_attributes"=>array('EVENT_ACTIONS','class'),
                        ),
                        */
                    
            );
            $config = json_encode($config, JSON_UNESCAPED_UNICODE);

            $file_dir = 'data/';
            $file_name = 'kisoku.pdf';
            //$file_name = 'doc_guide_v1.0.pdf';
            //$file_name = '2016sc.pdf';
            $file = "@".$file_dir.$file_name.";type=application/pdf";
            echo "file_name ===> $file_name";
            
            $header_cmd = "--header 'Content-Type: $content_type' --header 'Accept: $content_type_accept' ";
        }
        $cmd = "curl $user_cmd -X POST $header_cmd -F config='$config' -F 'file=$file' '$url' -F 'type=$content_type2'";
        Debug::dump($cmd);
        //exit;
        
        $cache_identifier = "watson_doc_res_data_".$file_name;
        try{
            $res = Cache::get($cache_identifier);
            echo "cache data exit!!!";
        }
        catch (\CacheNotFoundException $e){
            //キャッシュになければ。。。
            echo "cache data not exit!!!";
            // API実行処理
            /*
             * curl option
             *  -u USER[:PASS]	認証に用いるユーザー名USER, パスワードPASSを指定する（基本認証など）
             *  -X POST             POSTリクエストを送る
             *  -H --header        HTTP ヘッダを指定する。ユーザエージェントとかリファラとか
             *  -d, --data PARAM...	POSTリクエストとしてフォームを送信する。パラメータPARAMは「"value=name"」の形式で指定する
             */
            Debug::dump($cmd);
            
            //$res = exec($cmd, $ret);
            if ( !exec($cmd." 2>&1",$array) ) {
                //command失敗を検知して処理したい
                echo "NG";
                echo "<pre>";var_dump($array);echo "</pre>";
            }
            
            $res = shell_exec($cmd);

            /* うまくいかないからやめる
             //system関数を使った場合
             echo "<pre>system(cmd,ret)<hr>";print(system($cmd, $ret));echo "<hr></pre>";
             echo "<pre>ret<hr>";print($ret);echo "<hr></pre>";
             echo "<pre>json_decode(system(cmd))<hr>";var_dump(json_decode(system($cmd),true));echo "<hr></pre>";
             */
            
            \Cache::set($cache_identifier, $res, false);
        }
        //echo "<pre>ret<hr>";var_dump($ret);echo "</hr></pre>";
        Log::debug(print_r($res, true));
            
        //json => array
        $res = json_decode($res, true);
        Log::debug(print_r($res, true));
        
        self::makeCsv($res, $file_name);
        
    }
    
    /*
     * 
     */
    function makeCsv($res, $file_name)
    {

        $csv_name = $file_name . ".csv";
        $str = "";
        Debug::dump($res["answer_units"]);

        $fp = fopen(DOCROOT . $csv_name, 'w');
        foreach($res["answer_units"] as $key => $val){
            $title = $val["title"];
            //Debug::dump($title);
            $text = $val["content"][0]["text"];
            //Debug::dump($text);
            
            $str .= "\"$text\",\"$title\"\r\n";
        }
        $str = mb_convert_encoding($str, 'SJIS-win', 'UTF-8');
        fwrite($fp, $str);
        fclose($fp);
        
        /*
        // content-type: csv
        header("Content-Type: text/csv");
        // ファイル名をセット
        header("Content-disposition: attachment; filename=".$csv_name.".csv");
        // キャッシュをなしに
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Pragma: no-cache');
        echo $str;
        */
        
        echo "<a href='/$csv_name'>$csv_name</a>";
        return true;
    }    
    
    /*
     * 
     */
    function makeCsv2(){
        /* うまくいかないからやめる
         // CSVデータ化
         $add_res = array(array('123'=>'abc','456'=>'def'));
         $res = array_merge($add_res, $res);
         //Log::debug(print_r($res[0], true));
         Log::debug(print_r($res, true));
        
         $csv_name = "data.csv";
         // Response
         $response = new Response();
         $response->set_header('Content-Type', 'application/csv');
         // ファイル名をセット
         $response->set_header('Content-Disposition', 'attachment; filename="' . $csv_name . '"');
         $outputdata = Format::forge($res)->to_csv2();
         // キャッシュをなしに
         $response->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
         $response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
         $response->set_header('Pragma', 'no-cache');
        
         // CSVを出力
         echo $outputdata;
        
         // Response
         return $response;
         */
    }
}