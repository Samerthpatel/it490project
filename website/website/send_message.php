<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', '0ff');
	ini_set('log_errors', 'On');
	ini_set('error_log',"/var/www/html/it490project/website/my-errors.log");
	require_once('../rabbitmq/path.inc');
	 require_once('../rabbitmq/get_host_info.inc');
	 require_once('../rabbitmq/rabbitMQLib.inc');
	
	 $userid = $_SESSION["userid"];
	 $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	 if(isset($_POST['msg'])){		
	 $request = array();
		 $request['type'] = "sendchat";
		 $request['userid'] = $userid;
		 $request['msg'] = addslashes($_POST['msg']);
		 $request['id'] = $_POST['id'];
		 $response = $client->send_request($request);
	 }
?>
<?php
	session_start();
	include ('pages/conn.php');
	require_once('../rabbitmq/path.inc');
	require_once('../rabbitmq/get_host_info.inc');
	require_once('../rabbitmq/rabbitMQLib.inc');

	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	$userid = $_SESSION["userid"];
	if(isset($_POST['res'])){
		$request['type'] = "getchat";
		$request['userid'] = $userid;
		$request['id'] = $_POST['id'];
		$response = $client->send_request($request);
		$chat = json_decode($response, true);
	?>
	<?php
	?>	
		<div>
			<?php foreach($chat as $value) {?>
			</br>
			<?php echo $value['4']; ?><br>
			<?php echo $value['6']; ?>: <?php echo $value['2']; ?><br>
			<?php } ?>
		</div>
		<br>
	<?php
		}
?>