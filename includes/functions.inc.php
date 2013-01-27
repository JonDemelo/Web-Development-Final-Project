<?php

function process_script() {
	$action=strtolower(getRequest('action',true,'get'));
	$accepted_actions='login,account,logout';
	$accepted_actions_array = explode(",", $accepted_actions); 

	if (empty($action) || !in_array($action, $accepted_actions_array)) {
		$action='login';
	} /* elseif (!empty($_SESSION['userID'])){ // if already active
		$action='account';
	} */

	return get_template($action, $action()); 
}

function &account() {
	$HTML=array();
	return $HTML;
}

function &logout() {
	$_SESSION = array();
	Session_destroy();
	set_header('login'); 
	exit();
}

function &login(){
	$userID = 0;
	$HTML=array();
	$HTML['email']='';
	$HTML['birth']='';
	$HTML['login_error']='';

	if (getRequest('submitted',true,'post') !=='yes') {return $HTML;}
	
	foreach($HTML as $key=> &$value) {
		$value=getRequest($key, true, 'post');
	}

	$tempDate = dateconvert($HTML['birth']); // mm-dd-Y to dd-mm-Y
	// $time = strtotime($HTML['birth']); // m-d-yy
	$HTML['birth'] = ($tempDate === false) ? 'invalid' : $HTML['birth'];

	if (empty($HTML['email'])) {
		$HTML['login_error'] = 'Email Cannot be empty';
	} elseif (filter_var($HTML['email'],FILTER_VALIDATE_EMAIL) ===false) {
		$HTML['login_error'] ='Invalid Email Address';
	} elseif (empty($HTML['birth'])) {
		$HTML['login_error'] ='Date of birth cannot be empty';
	} elseif ($HTML['birth'] === 'invalid' || strtotime($tempDate) > strtotime('-1 week')) {
		$HTML['birth'] = '';
		$HTML['login_error'] ='Date of birth is not an acceptable choice';
	}
	
	if (empty($HTML['login_error'])) { // successful login
		$query = mysql_query("INSERT INTO project (email, birthday, ip) VALUES ('$HTML[email]', '$HTML[birth]', '$_SERVER[REMOTE_ADDR]')"); 
		set_SESSION('userID', $userID); 
		set_SESSION('birth', $HTML['birth']); 
		set_SESSION('birthddmmY', $tempDate);
		set_header('account'); //If no errors -> go to account
		exit();
	}

	return $HTML;
}

function dateconvert($orig){ // convert mm-dd-Y to dd-mm-Y and also opposite
	$array = array();
	$array = explode("-", $orig);
	$newdate = $array[1] . '-' . $array[0] . '-' . $array[2];
	return $newdate;
}

function validate_record($email, $birth) {
	if (empty($GLOBALS['DB'])) {die ('Database Link is not set'); }

	$row = array();
	$email = mysql_real_escape_string($email);
	$query = "SELECT * FROM project WHERE email='" . $email . "' AND birthday='" . $birth . "'LIMIT 1";
	$res = mysql_query($query);
	$row = mysql_fetch_array($res);
	return $row['id'];
}

function get_template($file, &$HTML=null){
	if ($file === "account") {
		if (get_SESSION('userID') == NULL) {
		   set_header('login'); 
		   exit();
		}
	}

	if ($file === "account") {
		$userID = get_SESSION('userID');
		$query = "SELECT * FROM project WHERE id='" . $userID . "' LIMIT 1";
		$res = mysql_query($query);
		$row = mysql_fetch_array($res);
		$HTML['birth'] = date('m-d-Y', strtotime($row['birthday']));
	}

	$content='';
	ob_start();
		if (@include(TMPL_DIR . '/' .$file .'.tmpl.php')):
		$content=ob_get_contents();
	endif;
	ob_end_clean();
	return $content;
}

function set_header($action=null) {
	$url = (empty($action)) ? urlhost() : urlhost().'?action='.$action;
	header('Location: '. $url );
	exit();
}

function get_SESSION($key) {
	return ( !isset($_SESSION[$key]) ) ? NULL : decrypt($_SESSION[$key]);
}

function set_SESSION($key, $value='') {
	if (!empty($key)) {
		$_SESSION[$key]=encrypt($value);
		return true;
	}
	return false;
}

function util_getenv ($key) {
	return ( isset($_SERVER[$key])? $_SERVER[$key]:(isset($_ENV[$key])? $_ENV[$key]:getenv($key)) );
}

function host ($protocol=null) {
	$host = util_getenv('SERVER_NAME');
	if (empty($host)) {	$host = util_getenv('HTTP_HOST'); }
	return (!empty($protocol)) ? $protocol.'//'.$host  : 'http://'.$host;
}

function urlhost ($protocol=null) {
	return host($protocol).$_SERVER['SCRIPT_NAME'];
}

function getRequest($str='', $removespace=false, $method=null){
	if (empty($str)) {return '';}
  		switch ($method) {
			case 'get':
				$data=(isset($_GET[$str])) ? $_GET[$str] : '' ;
				break;
			case 'post':
				$data=(isset($_POST[$str])) ? $_POST[$str] : '';
				break;
				
			default:
				$data=(isset($_REQUEST[$str])) ? $_REQUEST[$str] : '';
		}
 		
		if (empty($data)) {return $data;}
		
		
		if (get_magic_quotes_gpc()) {
			$data= (is_array($data)) ? array_map('stripslashes',$data) : stripslashes($data);	
		}

		if (!empty($removespace)) {
			$data=(is_array($data)) ? array_map('removeSpacing',$data) : removeSpacing($data);
		}

		return $data;
	}

function removeSpacing($str) {
		return trim(preg_replace('/\s\s+/', ' ', $str));
}
	
function encrypt($text, $SALT=null) {
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(SECURE_KEY), $text, MCRYPT_MODE_CBC, md5(md5(SECURE_KEY))));
} 
    
function decrypt($text, $SALT=null) { 
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(SECURE_KEY), base64_decode($text), MCRYPT_MODE_CBC, md5(md5(SECURE_KEY))), "\0");
}  	

function utf8HTML ($str='') {
  	   	return htmlentities($str, ENT_QUOTES, 'UTF-8', false); 
}

?>