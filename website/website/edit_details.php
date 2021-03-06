<?php
error_reporting(E_ALL);
ini_set('display_errors', '0ff');
ini_set('log_errors', 'On');
ini_set('error_log',"/var/www/html/it490project/website/my-errors.log");
	session_start();
	if (!isset($_SESSION['userid']) ||(trim ($_SESSION['userid']) == '')) {
	header('location:index.php');
    exit();
	}
	
	session_start();
	 require_once('../rabbitmq/path.inc');
	 require_once('../rabbitmq/get_host_info.inc');
	 require_once('../rabbitmq/rabbitMQLib.inc');
 
     $userid = $_SESSION["userid"];
	 $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	 $request = array();
		 $request['type'] = "editdetails";
		 $request['userid'] = $userid;
		 $response = $client->send_request($request);
?>
<!DOCTYPE html>
<html>
<head>
	<title>User - Profile</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="bootstrap-4.3.1-dist/css/style.css">
	<link rel="stylesheet" href="bootstrap-4.3.1-dist/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap-4.3.1-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap-4.3.1-dist/css/styles.css">
	<style>
  
    td {
    padding: 5px;
    text-align:center;     
    }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand text-success" href="#">
              Crypto coders
            </a>
            <button class="navbar-toggler" type="button" 
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
  
            <div class="collapse navbar-collapse"></div>
  
            <div class="collapse navbar-collapse" 
                 id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="pages/dash.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           href="home.php">
                          Chat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="pages/news.php">
                          News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="pages/research.php">
                          Research 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="pages/trade.php">
                          Trade 
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link"
                           href="logout.php">
                          Logout
                        </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link"
                           href="profile.php">
                          Profile
                        </a>                               
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<table class="table table-borderless" id="chat_room" align="center">
			<tr>
			<td>
			<h4>Hi there, <font color="black"><?php echo $response['name']; ?></font></h4>
			</td>
			</tr>
	<tr>
		<td><b>Details</b></td>
	</tr>
	<form name="form_edit" method="post" action="messaging/updateprofile.php">
	<tr>
		<td>Your Name:&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="name" name="name" value="<?php echo $response['name'];?>"  /></td>
	</tr>

	<tr>
		<td>Username:&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="username" name="username" value="<?php echo $response['username'];?>"  /></td>
	</tr>

	<tr>
		<td>Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" id="password" name="password" value="<?php echo $response['password']; ?>"/></td>
	</tr>

	<tr>
		<td>Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="email" id="email" name="email" value="<?php echo $response['email']; ?>"  /></td>
	</tr>

	<tr>
		<td>Phone:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="phone" name="phone" value="<?php echo $response['phone']; ?>" /></td>
	</tr>

	<tr>
		<td>Balance:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="balance" name="balance" value="<?php echo $response['balance']; ?>" /></td>
	</tr>

	<tr>
		<td>Add balance:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="add" name="add" value="0" required/></td>
	</tr>

    <tr>
		<td>Substract balance:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="sub" name="sub"/ value="0" required></td>
	</tr>

	<tr>
		<td><input type="submit" name="submit" class="btn btn-dark rounded submit px-3"></td>
	</tr>
	</form>

 	<tr>
		<td></td>
	</tr>

	<tr>
		<td><button type="button" class="btn btn-dark rounded submit px-3" /><a href="profile.php?userid=<?php echo $_SESSION['userid']; ?>">back to Profile</a></button></td>
	</tr>
</table>
</body>
</html>