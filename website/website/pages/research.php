<?php
error_reporting(E_ALL);
ini_set('display_errors', '0ff');
ini_set('log_errors', 'On');
ini_set('error_log',"/var/www/html/it490project/website/my-errors.log");
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<?php
$name = htmlspecialchars($_REQUEST['stock']); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../../rabbitmq/path.inc');
	require_once('../../rabbitmq/get_host_info.inc');
	require_once('../../rabbitmq/rabbitMQLib.inc');

    $userid = $_SESSION["userid"];
    $client = new rabbitMQClient("testRabbitMQ.ini","dmzServer");
    $request = array();
        $request['type'] = "getdata";
        $request['userid'] = $userid;
        $request['name'] = htmlspecialchars($_REQUEST['stock']);
        $response = $client->send_request($request);

    $url ="https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY_EXTENDED&symbol=$name&interval=60min&slice=year1month1&apikey=5H2X5E07Q3FPXXP9";
    $data = file_get_contents($url);

    $row = explode("\n",$data);
    $count = count($row)-1;
    for($x=0; $x< $count; $x++)
    {
        $day[] = explode(",",$row[$x]);
    }
}
?>
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
                        <a class="nav-link" href="dash.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           href="../home.php">
                          Chat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="news.php">
                          News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="research.php">
                          Research 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="trade.php">
                          Trade 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="../logout.php">
                          Logout
                        </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link"
                           href="../profile.php">
                          Profile
                        </a>                               
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container">
<div class="col-md-12" style="margin-top:20px;">
    <div style="text-align:center;">
        <h1>Research </h1>
    </div>
</div>
    <div class="row" style="margin-left:350px;margin-top:50px;">
            <form class="form-inline" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <div class="form-group">
                        <input type="text" id="stock" name="stock" placeholder="Enter Company Symbol" value="TSLA" class="form-control" id="name">
                </div>
                <input type="submit" class="btn btn-dark rounded submit" style="margin-left:20px;"value="Submit" />
            </form>
        </form>
    </div>

    <div class="row" style="margin-top:50px;">
            <table class="table table-hover">
            <thead>
                    <tr>
                            <th>#</th>
                            <th><?php echo $day[0][0]?></th>
                            <th><?php echo $day[0][1]?></th>
                            <th><?php echo $day[0][2]?></th>
                            <th><?php echo $day[0][3]?></th>
                            <th><?php echo $day[0][4]?></th>
                            <th><?php echo $day[0][5]?></th>
                    </tr>
            </thead>
            <tbody>
                <?php
                for($x=1; $x <$count; $x++)
                {
                    $day[] = explode(",",$row[$x]);
                    echo "<tr>";
                    echo "<th>".$x."</th>";
                    echo "<td>".$day[$x][0]."</td>";
                    echo "<td>".$day[$x][1]."</td>";
                    echo "<td>".$day[$x][2]."</td>";
                    echo "<td>".$day[$x][3]."</td>";
                    echo "<td>".$day[$x][4]."</td>";
                    echo "<td>".$day[$x][5]."</td>";
                    echo "<tr>";
                }   
                ?>
            </tbody>
            </table>
    </div>
</div>
<?php
require_once('../../rabbitmq/path.inc');
require_once('../../rabbitmq/get_host_info.inc');
require_once('../../rabbitmq/rabbitMQLib.inc');

        $userid = $_SESSION["userid"];
	    $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	    $request = array();
		$request['type'] = "tradehistory";
		$request['userid'] = $userid;
		$response = $client->send_request($request);
        $history = json_decode($response, true);    
?>
<div>
<div style="text-align:center;">
        <h1>Trade History: </h1>
    </div><?php foreach($history as $value) {?>
  <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Company Ticker:</th>
      <th scope="col">Shares:</th>
      <th scope="col">Price:</th>
      <th scope="col">Total invested:</th>
      <th scope="col">Date</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?=$value['1']?></td>
      <td><?=$value['3']?></td>
      <td><?=$value['2']?></td>
      <td><?=$value['4']?></td>
      <td><?=$value['5']?></td>
    </tr>
  </tbody>
</table>
<?php } ?>
</div>
</body>
</html>