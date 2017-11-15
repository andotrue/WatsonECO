<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Watson Conversation Apitest</title>
	<?php echo Asset::css('bootstrap-3.3.7.min.css'); ?>
	
	<script src="https://js.pusher.com/4.0/pusher.min.js"></script>
	<script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('af84690ae32132ce6058', {
      encrypted: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      alert(data.message);
    });
  </script>
</head>

<body>
	<?php echo Form::open("/apitestCnv/index/$ch"); ?>

	<?php echo Form::label('実行API', 'api'); ?>
	<?php echo Form::select('selapi', (Input::param("selapi"))? : "aws", $selapis, array("class"=>"form-control"));?>

	<?php echo Form::label('メッセージ', 'message'); ?>
	<?php echo Form::input('message', (Input::param("message"))? : "", array('type'=>'text', 'class'=>'form-control'));; ?>
	<?php echo Form::hidden('context', isset($context)? $context : "", array('type'=>'', 'class'=>'')); ?>

	<?php echo Form::input('apibtn', '送信', array('type'=>'submit', 'class'=>'form-control btn btn-success'));; ?>
	<?php echo Form::close(); ?>
	
	<?php 
    	if(isset($res['output']['text'])){
    	    foreach($res['output']['text'] as $val){
    	        echo "<p>$val</p>";
    	    }
    	}
    	else{
    	    echo "<p>None Output</p>";
    	}
	?>
	
	<h3>実行API</h3>
	<?php echo "<pre>";var_dump($cmd);echo "</pre>";?>

	<h3>レスポンス</h3>
	<?php 
	   if(isset($res)){
    	foreach($res as $res_key => $res_val){
            echo "res_key : $res_key";
            echo "<pre>"; var_dump($res_val); echo "</pre>";
        }
	   }
    ?>
    <h3>all レスポンス</h3>
	<?php echo "<pre>";var_dump($res);echo "</pre>";?>
</body>
</html>