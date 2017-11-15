<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Watson NLC Apitest</title>
	<?php echo Asset::css('bootstrap-3.3.7.min.css'); ?>
</head>

<body>
	<?php echo Form::open('/apitestNlc/'); ?>

	<?php echo Form::label('実行API', 'api'); ?>
	<?php echo Form::select('selapi', (Input::param("selapi"))? : "aws", $selapis, array("class"=>"form-control"));?>

	<?php echo Form::label('メッセージ', 'message'); ?>
	<?php echo Form::input('message', (Input::param("message"))? : "", array('type'=>'text', 'class'=>'form-control'));; ?>

	<?php echo Form::label('classifier_id', 'classifier_id'); ?>
	<?php echo Form::input('classifier_id', (Input::param("classifier_id"))? : "", array('type'=>'text', 'class'=>'form-control'));; ?>

	<?php echo Form::label('file_name', 'file_name'); ?>
	<?php echo Form::input('file_name', (Input::param("file_name"))? : "", array('type'=>'text', 'class'=>'form-control'));; ?>

	<?php echo Form::label('classifier_name', 'classifier_name'); ?>
	<?php echo Form::input('classifier_name', (Input::param("classifier_name"))? : "", array('type'=>'text', 'class'=>'form-control'));; ?>

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