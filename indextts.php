<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
session_start();
function compileString($str){
	//echo "INSIDE";
	foreach ($_GET as $key => $value) {
		//echo "key= ".$key."value= ".$value."\n";
		$str=str_replace($key,$value,$str);
		//echo $str."\n";
	}
	//echo "OUTSIFE";
	return $str;
}

function callAPI($method, $url, $data){
	
	$curl = curl_init();
	switch ($method){
	   case "POST":
		  curl_setopt($curl, CURLOPT_POST, 1);
		  if ($data)
			 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		  break;
	   case "PUT":
		  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		  if ($data)
			 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
		  break;
	   default:
		  if ($data)
			 $url = sprintf("%s?%s", $url, http_build_query($data));
	}
	// OPTIONS:
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	   'APIKEY:8a262058-a559-11ea-9fa5-0200cd936042',
	   'Content-Type: application/json',
	));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	// EXECUTE:
	$result = curl_exec($curl);
	if(!$result){die("Connection Failure");}
	curl_close($curl);
	return $result;
 }


function buildJSON($id){
	$db = mysqli_connect("localhost","root","1","chat") or die("<strong>ss</strong>");
	$sql="select * from says where id=$id";
	//echo $sql;
	$query=mysqli_query($db,$sql);
	$row = mysqli_fetch_array($query,MYSQLI_ASSOC);

	if(isset($_GET['sname']) && $row['t1']=='RESULT'){
		//$sql2="select * from student where name like '$_GET[sname]';";
		$query2=mysqli_query($db,$sql2);
		//$HTML=" This is the dummy Marks table obtained from the DB <br><table border=8 style='position:relative;height:50%;width:50%;display:table-cell;'><tr><th>USN<th>Name<th>M1<th>M2<th>M3<th>M4";
		while($row2 = $query2->fetch_assoc()){
			//$HTML=$HTML."<tr><td>$row2[usn]<td>$row2[Name]<td>$row2[M1]<td>$row2[M2]<td>$row2[M3]<td>$row2[M4]";
		}
		$HTML=$HTML."</table>";
		$says= array_filter([$HTML]);
	}
	else if(isset($_GET['user_dept']) && isset($_GET['te']) && $row['t1']=='TEACHER'){
		$sql2="select * from teachers where name like '$_GET[te]' and dept='$_GET[user_dept]' limit 1;";
		//echo $sql2;
		$query2=mysqli_query($db,$sql2);
		$row2 = $query2->fetch_assoc();
		$HTML="$row2[image]<br>Name: <b>$row2[name]</b><br><br>$row2[tagline]";
		$says= array_filter([$HTML]);
	}
	//new added
	else{
		$says= array_filter([$row['t1'],$row['t2'],$row['t3'],$row['t4']]);
	}
	$UNIQID=uniqid();
	$jsq="var monkeyList = new List('$UNIQID', {
	valueNames: ['name'],
		page: 3,
		pagination: true });";
	$opt=array();
	$path=array();
	if($row['cqb']==1){
		// error_log("sanjay" . $row["id"]);
		$sql2=compileString($row['cq']); //replaceing cusotmq query strings
		$query2=mysqli_query($db,$sql2);
		
		if($row["id"] == "53"){
			// check where given value is number of email

			if (filter_var($_SESSION["number"], FILTER_VALIDATE_EMAIL)){
				// given input is a email
				if ($_GET["otp"] == $_SESSION["gen_otp"]){
					// Auth success
					$msg = "OTP verification successfull<br>";
					// for student result display
					//echo "\n\n\n\n".$sql2;
					$i=0;
					$branch = $_GET["branch"];
					$sem = $_GET["sem"];
					error_log("hellow world " . json_encode($_GET));
					$sql2 = "select student.* from student join users on (users.usn = student.usn) where users.email like '" . $_SESSION['number'] . "' and student.branch like '" . $branch . "' and student.sem like '" . $sem . "'";
					// error_log("hello world " . $sql2);
					// $sql2 = "select student.* from student join users on (users.usn = student.usn) where users.email like '" . $_SESSION['number'] . "'";
					$query2=mysqli_query($db,$sql2);
					if(mysqli_num_rows($query2) > 0){
						$msg = $msg . "Your result is <br><table style='position:relative;height:50%;width:50%;display:table-cell;'><tr><th>USN<th>Name<th>M1<th>M2<th>M3<th>M4";
						while ($res_row = $query2 -> fetch_row()) {
							$msg=$msg."<tr><td>$res_row[0]<td>$res_row[1]<td>$res_row[2]<td>$res_row[3]<td>$res_row[4]<td>$res_row[5]";
							break;
						}
					} 
				}else{
					// mismatch otp
					$msg = "OTP mismatch. Chat bot exiting. Please try again later";
				}
			}else{
				// given input is a number

				// number is present in the database send OTP
				$API_KEY = "8a262058-a559-11ea-9fa5-0200cd936042";
				//The url you wish to send the POST request to
				$otp = $_GET["otp"];
				$url = "https://2factor.in/API/V1/" . $API_KEY . "/SMS/VERIFY/" . $_SESSION['otp_session'] . "/" . $otp;
				error_log("url " . $url);
				error_log("otp ", $otp);
				error_log("test session " . $_SESSION["otp_session"]);

				$get_data = callAPI('GET', $url, false);
				$response = json_decode($get_data, true);
				error_log(json_encode($get_data));
				error_log("testing " . $sql2);
				if ($response["Status"] == "Error"){
					$msg = "OTP mismatch. Chat bot exiting. Please try again later";

				}else{
					$msg = "OTP verification successfull<br>";
					// for student result display
					//echo "\n\n\n\n".$sql2;
					$i=0;
					$sql2 = "select student.* from student join users on (users.usn = student.usn) where users.phone like '" . $_SESSION['number'] . "'";
					$query2=mysqli_query($db,$sql2);
					if(mysqli_num_rows($query2) > 0){
						$msg = $msg . "Your result is <br><table style='position:relative;height:50%;width:50%;display:table-cell;'><tr><th>USN<th>Name<th>M1<th>M2<th>M3<th>M4";
						while ($res_row = $query2 -> fetch_row()) {
							$msg=$msg."<tr><td>$res_row[0]<td>$res_row[1]<td>$res_row[2]<td>$res_row[3]<td>$res_row[4]<td>$res_row[5]";
							break;
						}
					} 
				}
			}
			session_destroy();
			$says= array_filter([$msg]);

		}else if($row["id"] == "51"){
			// for OTP feature
			// check whether the number is present in the database or not
			if (mysqli_num_rows($query2) > 0){
				// number is present in the database send OTP
				$API_KEY = "8a262058-a559-11ea-9fa5-0200cd936042";

				// generate random number
				$gen_otp=rand(1000,9999);
				error_log("generated OTP " . $gen_otp);

				//The url you wish to send the POST request to
				$url = "https://2factor.in/API/V1/" . $API_KEY . "/SMS/+91" . $_GET["num"] . "/" . $gen_otp;

				$get_data = callAPI('GET', $url, false);
				$response = json_decode($get_data, true);
				error_log(json_encode($get_data));
				$_SESSION["otp_session"] = $response["Details"];
				$_SESSION["number"] = $_GET["num"];
				$msg = "OTP sent successfully to your number";
				$says= array_filter([$msg]);
				
			}else{
				$sql3 = "select * from users where email like '" . $_GET["num"] . "' limit 1";
				error_log($sql3);
				$_SESSION["number"] = $_GET["num"];
				$query3 = mysqli_query($db,$sql3);
				if (mysqli_num_rows($query3) > 0){
					// add mail service here
					// generate random number
					$gen_otp=rand(1000,9999);
					$_SESSION["gen_otp"] = $gen_otp;

					// credentials
					$emailid = "hsanjay.1998@gmail.com";
					$pass = "samjayhhh";

					$mail = new PHPMailer;

					$mail->isSMTP();
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPAuth = true;
					$mail->Port = 587;

					$mail->Username = $emailid;
					$mail->Password = $pass;
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

					$mail->setFrom($emailid, 'Admin');
					
					$mail->addAddress($_GET["num"]);

					$mail->addReplyTo($emailid);

					$mail->WordWrap = 50;
					$mail->isHTML(true);

					$mail->Subject = 'One Time Password Request';
					$mail->Body    = 'your one time password is - <b>'.$gen_otp.'</b>';
					$mail->AltBody = 'your one time password is - '.$gen_otp;
						if(!$mail->send()):
							// sent successfully
							$_SESSION["number"] = $_GET["num"];
							$msg = "OTP sent successfully to your email";
							$says= array_filter([$msg]);

						else:
							// error
							error_log("error");
						endif;


				}else{
					// number is present in the database error msg
					$says= array_filter(["invalid number or email. Please try again later."]);
					$i=0;
					$replay=[];
					foreach ($opt as $val){
						$small=["question"=>$opt[$i],"answer"=>($path[$i]==null?0:$path[$i])];
						$replay[$i]=$small;
						$i++;
					}
					$va= array_filter([$row['var']]);
					$next= array_filter(["54"]);
					error_log("testing 1 " . json_encode($says));
					error_log("testing 1 " . json_encode($va));
					error_log("testing 1 " . json_encode($next));
					error_log("testing 1 " . json_encode($row["type"]));
					error_log("testing 1 " . json_encode($jsq));
					$dict=["ice" => ["says" =>array_filter($says),"reply" =>$replay,"var" => $va, "next" => $next, "type" => $row['type'], "jsq" => $jsq]];
					return json_encode($dict);
				}
				
			}
		}else if ($row["id"] == "100"){
			$email = $_GET["email_"];
			$sql2 = "INSERT INTO `users_emailid` (`email`) VALUES ('$email')";
			if(mysqli_query($db, $sql2)){
				error_log("email saved successfully");
			}

			error_log("email " . $_GET["email_"]);
		}
		else if($row["id"] == "41"){
			
			// for student result display
			//echo "\n\n\n\n".$sql2;
			$i=0;
			$msg = "";
			if(mysqli_num_rows($query2) > 0){
				$msg = "Your result is <br><table style='position:relative;height:50%;width:50%;display:table-cell;'><tr><th>USN<th>Name<th>M1<th>M2<th>M3<th>M4";
				while ($res_row = $query2 -> fetch_row()) {
					$msg=$msg."<tr><td>$res_row[0]<td>$res_row[1]<td>$res_row[2]<td>$res_row[3]<td>$res_row[4]<td>$res_row[5]";
					break;
				}
			}else{	
				$msg = "Incorrect Details. Please try again.";
				
			} 
			$says= array_filter([$msg]);
		}else{
			
			//echo "\n\n\n\n".$sql2;
			$i=0;

			$HTML="These are availiable Students: Enter the Name to fetch the result. [case insesnsitive] 
				<div id='$UNIQID'>
				<input type='text' class='search' placeholder='Enter Name for here' />
				<ul class='list'>";
			while($row2 = mysqli_fetch_array($query2)){
				//$opt[$i]=$row2[0];
				//$path[$i]=$row['next'];
				$i++;
				$HTML=$HTML."<li><p class='name'>$row2[0]</p></li>"." ";
			}
			$HTML=$HTML.    
				"</ul>
				<ul class='pagination'></ul>
				</div>";
			$says= array_filter([$HTML]);
		}

	}
	else{
		$opt=array_filter(explode(",",$row['opt']));
		$path=array_filter(explode(",",$row['next']));
	}

	$i=0;
	$replay=[];
	foreach ($opt as $val){
		$small=["question"=>$opt[$i],"answer"=>($path[$i]==null?0:$path[$i])];
		$replay[$i]=$small;
		$i++;
	}
	$va= array_filter([$row['var']]);
	$next= array_filter(explode(",",$row['next']));
	error_log("testing 2 " . json_encode($says));
	error_log("testing 2 " . json_encode($va));
	error_log("testing 2 " . json_encode($next));
	error_log("testing 2 " . json_encode($row["type"]));
	error_log("testing 2 " . json_encode($jsq));
	$dict=["ice" => ["says" =>array_filter($says),"reply" =>$replay,"var" => $va, "next" => $next, "type" => $row['type'], "jsq" => $jsq]];
	return json_encode($dict);
}
if(isset($_GET['id'])){
	echo buildJSON($_GET['id']);exit();
}
else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Keyboard Input for chat-bubble</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" media="all" href="../component/styles/setup.css">
	<link rel="stylesheet" media="all" href="../component/styles/says.css">
	<link rel="stylesheet" media="all" href="../component/styles/reply.css">
	<link rel="stylesheet" media="all" href="../component/styles/typing.css">
	<link rel="stylesheet" media="all" href="../component/styles/input.css">
	<style>
	body {
		background: #dcdde0;
	}
	.bubble-container {
		height: 100vh;
	}
	.bubble-container .input-wrap textarea {
		margin: 0;
		width: calc(100% - 30px);
	}
	/* Added */
	.pagination li {
	  display:inline-block;
	  padding:5px;
	}
	</style>
</head>
<body>
		<div id="chat" ></div>

<script src="../component/Bubbles.js"></script>
<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"> </script>
<script src="./jquery.min.js"></script>
<script src="./list.min.js"></script>
<script>
	

$(document).ready(function(){
    $(document).click(function(event){
		if(event.target.nodeName == "SPAN" && event.target.className == "bubble-content"){
			var synth = window.speechSynthesis;
			var voices = [];
			PopulateVoices();
			if(speechSynthesis !== undefined){
				speechSynthesis.onvoiceschanged = PopulateVoices;
			}

			// btnSpeak.addEventListener('click', ()=> {
				var text = event.target.innerHTML;	
				var decoded = $("<span/>").html(event.target.innerHTML).text();
				var toSpeak = new SpeechSynthesisUtterance(text);
				var selectedVoiceName = "Google UK English Female";
				// var = selectedVoiceName = "Google हिन्दी";
				voices.forEach((voice)=>{
					if(voice.name === selectedVoiceName){
						toSpeak.voice = voice;
					}
				});
				synth.speak(toSpeak);
			// });

			function PopulateVoices(){
				voices = synth.getVoices();
			}
		}
    });
});
</script>
	<script>



	var chatWindow = new Bubbles(document.getElementById("chat"), "chatWindow", {
	inputCallbackFn: function(o) {
		dict[temp_var]=o.input;
		nextID=replaceSTRINGS(nextID);
		console.log("ASSSSSSSSSSSSSSSSSSSSSSSSSSSSSS"+dict[temp_var]+"   "+o.OPT);
		console.log(dict);
		var miss = function() {
			chatWindow.talk(
		{
			"i-dont-get-it": {
			says: [
				"Sorry, I don't get it 😕. Pls repeat? Or you can just click below 👇"
			],
			reply: o.convo[o.standingAnswer].reply
		}
		},
			"i-dont-get-it"
		)
		}

		var match = function(key) {
			setTimeout(function() {
				chatWindow.talk(convo, key) // restart current convo from point found in the answer
			}, 600)
		}

		// sanitize text for search function
		var strip = function(text) {
			return text.toLowerCase().replace(/[\s.,\/#!$%\^&\*;:{}=\-_'"`~()]/g, "")
		}

		// search function
		if(json_data.ice.type=="input"){
			dict[temp_var]=o.input;

			nextID=replaceSTRINGS(nextID);
			setTimeout(function() {loadNext() }, 3000);
		}
		else if(json_data.ice.type=="opt"){
			//console.log("SADDDDDDDDDDDD"+o.OPT);
			dict[temp_var]=o.input;
			//console.log("AT INPUY");

			var found = false
				o.convo[o.standingAnswer].reply.forEach(function(e, i) {
					strip(e.question).includes(strip(o.input)) && o.input.length > 0
						? (found = e.answer)
						: found ? null : (found = false)
				})
				found ? match(found) : miss()
		}
		else{
			setTimeout(function() {loadNext() }, 3000);
		}

	}
}) // done setting up chat-bubble

	// conversation object defined separately, but just the same as in the
	// "Basic chat-bubble Example" (1-basics.html)
	var convo = {
	0: {
	says: ["Hi", "Would you like banana or ice cream?"],
		reply: [
	{
		question: "Banana",
			answer: "banana"
	},
	{
		question: "Ice Cream",
			answer: "ice-cream"
	}
],
	var: ["a"]
	}
	}
	// pass JSON to your function and you're done!
	var json_data=<?php echo buildJSON(0);?>;
	var nextID=0;
	var temp_var="";
	var dict = {};
	function updateGlobals(){
		temp_var=json_data["ice"]["var"][0];
		nextID=json_data["ice"]["next"][0];
		nextID=replaceSTRINGS(nextID);
		setTimeout(  eval(json_data.ice.jsq),2000);
	}
	function addChat(){
		preProcessJSON();
		setTimeout(function() {chatWindow.talk(json_data,"ice") }, 600);
		updateGlobals();

		if(json_data.ice.type=="msg"){
			setTimeout(function() {loadNext() }, 3000);
			console.log("INSIDE MSG BOCX");
		}
		setTimeout(function() {eval(json_data.ice.jsq) }, 3000);
	}
	function replaceSTRINGS(s){
		for(var key in dict) {
			s=replaceAll(s,key,dict[key]);
		}
		return s;
	}
	function escapeRegExp(string){
		return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
	}
	function replaceAll(str, term, replacement) {
		return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
	}
	function preProcessJSON(){
		for (i=0;i<json_data.ice.says.length;i++){
			json_data.ice.says[i]=replaceSTRINGS(json_data.ice.says[i]);
		}

	}
	addChat();
	function loadNext(){
		nextID=replaceSTRINGS(nextID);
		$.getJSON('indextts.php?id='+nextID, function(jd) {
			json_data=jd;
			console.log('indextts.php?id='+nextID);
			console.log(json_data);
			addChat();
		});
		delete dict["teacher_name"];
	}
	function answer(boxvalue,boxname){
		//alert("you clicked"+x+"  "+z);
		dict[temp_var]=boxname;
		nextID=replaceSTRINGS(boxvalue);
		setTimeout(function() {loadNext() }, 2000);
		eval(json_data.ice.jsq);
	}

	</script>

	<script>
	
</script>
</body>
<?php
}
?>



<!--
+-------+--------------+------+-----+---------+-------+
| Field | Type         | Null | Key | Default | Extra |
+-------+--------------+------+-----+---------+-------+
| id    | int(11)      | NO   | PRI | <null>  |       |
| t1    | varchar(200) | YES  |     | <null>  |       |
| t2    | varchar(200) | YES  |     | <null>  |       |
| t3    | varchar(200) | YES  |     | <null>  |       |
| t4    | varchar(200) | YES  |     | <null>  |       |
| opt   | varchar(300) | YES  |     | <null>  |       |
| var   | varchar(60)  | YES  |     | <null>  |       |
| next  | varchar(60)  | YES  |     | <null>  |       |
| type  | varchar(60)  | YES  |     | <null>  |       |
| cq    | varchar(600) | YES  |     | <null>  |       |
| cqb   | int(11)      | YES  |     | <null>  |       |
+-------+--------------+------+-----+---------+-------+
-->
