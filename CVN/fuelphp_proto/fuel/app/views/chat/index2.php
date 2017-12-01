<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<title>Watson chat test</title>
<!-- <link rel="stylesheet" href="./common.css"> -->
<?= Asset::css($css); ?>
<?= Asset::js($js,array("charset"=>"UTF-8"))?>
</head>
<body>

<script>
/*
console.log();
$(document).ready(function(){
	$('#reqMsg').focus();
});
$(window).load(function () {
	$('#reqMsg').focus();
});
*/
//$('#reqMsg').focus();
$(function(){
	//$('#reqMsg').focus();
	
	var $form = $("#msg_post");
	$form.find('#reqMsg').focus();
	var $button = $form.find('.btn');
	$button.attr('disabled', false);
	//送信ボタンクリック
	$('#msg_post').submit(function(event) {
		// HTMLでの送信をキャンセル
		event.preventDefault();

		// 操作対象のフォーム要素を取得
		var $form = $(this);

		// 送信ボタンを取得
		// （後で使う: 二重送信を防止する。）
		var $button = $form.find('.btn');
		console.dir($form);
		var date = new Date(jQuery.now()).toLocaleString();
		//alert(date);

		$reqMsg = $form.find('#reqMsg').val();
		$form.find('#reqMsg').focus();
		//$('#reqMsg').val("");
		
		//alert($reqMsg);
		var appned_li = 
		        "<hr>"+
		        "<div class='media'>"+
    		        "<div class='media-body'>"+
        		        "<h4 class='media-heading'>Date:"+date+"</h4>"+
        		        "<div>"+$reqMsg+"</div>"+
    		        "</div>"+
    		        "<div class='media-right'>"+
    			        "<a href='#' class='icon-rounded'>Me</a>"+
    		        "</div>"+
		        "</div>";
	        
	    $("div.panel-body").append(appned_li);

	    // 送信
		$.ajax({
			url: $form.attr('action'),
			type: $form.attr('method'),
			data: $form.serialize() + '&delay=1',  // （デモ用に入力値をちょいと操作します）
			timeout: 10000,  // 単位はミリ秒

			// 送信前
			beforeSend: function(xhr, settings) {
			    // ボタンを無効化し、二重送信を防止
			    $button.attr('disabled', true);
			    $form.find('#reqMsg').val("");
			},
			// 応答後
			complete: function(xhr, textStatus) {
			    // ボタンを有効化し、再送信を許可
			    $button.attr('disabled', false);
			},
			
			// 通信成功時の処理
			success: function(result, textStatus, xhr) {
				var result = JSON.parse( result );
				console.dir(result);
			    // 入力値を初期化
			    $form[0].reset();

			    //console.dir(result['selectlists']);
			    //$(':text[name="message"]').val("ほげほげ");
			    //alert($(':text[name="message"]').val());
			    if(result['status'] == 'success'){
				    $resMsg = result['message'];
				    //alert($resMsg);
				    var selectlists = result['selectlists'];
				    console.dir(selectlists);
				    console.dir(selectlists.length);

				    var date = new Date(jQuery.now()).toLocaleString();

				    //レスポンス
				    var max = 100000;
				    var min = 1;
				    var random = Math.floor( Math.random() * (max+1-min) ) + min;
				    bcname = "bubble"+random;
					var appned_li = 
            					"<hr>"+
            			        "<div class='media'>"+
                			        "<div class='media-left'>"+
	                			        "<a href='#' class='icon-rounded'>Rb</a>"+
                			        "</div>"+
                			        "<div class='media-body'>"+
	 	              			        "<h4 class='media-heading'>Date:"+date+"</h4>"+
                			        //"<div>"+$resMsg+"</div>"
                			        "<div id='"+bcname+"'></div>"
                			        ;

                    //質問リスト		        
  		            if(selectlists != ''){
  		            	appned_li = appned_li + "<br><div>例えばこんな質問。。。</div>";
                    	appned_li = appned_li + "<div>";
    				    for(var i=0, len=selectlists.length; i<len; i++){
    				    	console.dir(selectlists[i]);
    				    	appned_li = appned_li + "<button class='btn btn-success sellistBt' onclick='selclick(\""+selectlists[i]+"\"); return false;'>"+selectlists[i]+"</button>"; 
    				    }
    				    appned_li = appned_li + "</div>";
  		            }

				    appned_li = appned_li + "</div>";

			        $("div.panel-body").append(appned_li);
			        $(':hidden[name="context"]').val(result['context']);

					bubble(bcname, $resMsg);
			        
					var h = $('#reqMsg').offset().top;
					$('#reqMsg').focus();
					//alert(h);
					h = h + 999;
					//alert(h);
					$('html,body').animate({ scrollTop: h }, 'slow');

			    }
			    else if(result['status'] == 'error'){
				    $('#result_ok').text('');
				    $('#result_ng').text(result['message']);
			    }
			},
			
			// 通信失敗時の処理
			error: function(xhr, textStatus, error) {
				console.dir(xhr);
				$('#result_ok').text('');
			    $('#result_ng').text('通信失敗');
			}

		});
		var h = $('#reqMsg').offset().top;
		$('#reqMsg').focus();
		//alert(h);
		//alert(h+999);
		$('html,body').animate({ scrollTop: h+9999 }, 'slow');
		//$('html,body').animate({ scrollTop: 0 }, 'slow');
		//$("html,body").animate({scrollBottom:0});
		//$('html,body').animate({scrollTop: $('#target')[0].scrollHeight}, 'fast');
	});
	//$('html,body').animate({ scrollTop: $('#reqMsg').offset().top }, 'slow');

});

function selclick(selword){
	$(':text[name="message"]').val(selword);
	//alert($(':text[name="message"]').val());
	$('#msg_post').submit();
}

$(document).ready(function() {
    var $element = $('#bubble');
    var newText = "<?php echo $output_message; ?>なにかご質問はございますか？";

    bubbleText({
        element: $element,
        newText: newText,
        speed: 3000,
        //repeat: Infinity,
    });

});

function bubble($e, $nt){
    var $element = $('#'+$e);
    var newText = $nt;

    bubbleText({
        element: $element,
        newText: newText,
        speed: 3000,
        //repeat: Infinity,
    });
}

</script>



<div class="page">
  <section class="panel panel-default">
    <div class="panel-heading">
      <div class="heading-main">
      	Watsonチャットボット
      </div>
    </div>
    <div class="panel-body">
      <div class="media">

<div class="row">
  <div class="col-xs-1 col-s-1 col-md-1">
        <div class="media-left">
          <a href="#" class="icon-rounded">Rb</a>
        </div>
  </div>
  <div class="col-xs-11 col-s-11 col-md-11">
        <div class="media-body">
          <h4 class="media-heading"></h4>
          <!-- 
          <div><?php echo $output_message; ?></div>
          <div>なにかご質問はございますか？</div> 
          -->
          <div id="bubble"></div>
          <?php if(isset($selectlists)){ ?>
              <br>
              <div>例えばこんな質問。。。</div>
              <div>
                <?php foreach($selectlists as $selectlist){ ?>
                <button class='btn btn-success sellistBtn' onclick='selclick("<?php echo $selectlist; ?>"); return false;'><?php echo $selectlist; ?></button>
                <?php } ?>
    		  </div>
		  <?php } ?>
        </div>
  </div>
</div>


      </div>
    </div>
  </section>

  <?php echo Form::open(array('action'=>'/chat/ajax/'.$ch,'id'=>"msg_post")); ?>
  <footer class="footer-container">
    <div class="footer-form">
      <div class="input-group">
	    <!-- 
        <input type="text" class="form-control" placeholder="Comment">
        <span class="input-group-btn"><button class="btn btn-info" type="button">SEND</button></span>
         -->
    	<?php echo Form::input('message', "", array('type'=>'text', 'class'=>'form-control', 'id' => 'reqMsg', 'placeholder'=>'Comment'));; ?>
    	<?php echo Form::hidden('message2', "", array('type'=>'', 'class'=>'')); ?>
    	<?php echo Form::hidden('context', isset($context)? $context : "", array('type'=>'', 'class'=>'')); ?>
        <span class="input-group-btn"><?php echo Form::input('apibtn', '送信', array('type'=>'submit', 'class'=>'btn btn-info'));; ?></span>
      </div>
    </div>
  </footer>
  　<?php echo Form::close(); ?>
    <div id="target">
  </div>




</div>
<script>

/*
var $form = $("#msg_post");
$form.find('#reqMsg').focus();
$('#reqMsg').focus();
$(function(){
	$('#reqMsg').focus();
});
*/
/*
$(document).ready(function(){
	$('#reqMsg').focus();
});
*/
</script>

</body>
</html>