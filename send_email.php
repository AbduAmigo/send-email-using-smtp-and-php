<?php
if(isset($_POST['email'])) {
	
	require_once "Mail.php";
	header('Content-Type: text/html; charset=utf-8');
	
	$sector     = $_POST['sector'];   // required
	$name 		= $_POST['name'];     // required
	$activity 	= $_POST['activity']; // required
    $email_from = $_POST['email'];    // required
    $phone      = $_POST['phone'];    // required
	$type     	= $_POST['type'];    // required
	
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
	if(!preg_match($email_exp,$email_from)) {
	    $error_message .= 'Unknown email address or incorrect<br />';
	}
	 
	if(strlen($error_message) > 0) {
	    echo json_encode(array('result' => 0, 'msg' => $error_message));
		die();
	}
  
     
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
 
	// new design 
	
	$body = '<html><head><meta charset=utf-8" /></head><body>';
	$body .= '<h2 style="text-align:right">نموذج التقديم</h2>';
	$body .= '<table rules="all" style="border-color: #666;width: 100%;direction: rtl;" cellpadding="10">';
	$body .= "<tr style='background: #eee;'><td><strong>الجهة:</strong> </td><td>" . clean_string($sector) . "</td></tr>";
	$body .= "<tr><td><strong>الإسم:</strong> </td><td>" . clean_string($name) . "</td></tr>";
	$body .= "<tr><td><strong>النشاط:</strong> </td><td>" . clean_string($activity) . "</td></tr>";
	$body .= "<tr><td><strong>البريد الإلكتروني:</strong> </td><td>" . clean_string($email_from). "</td></tr>";
	$body .= "<tr><td><strong>رقم الهاتف:</strong> </td><td>" . clean_string($phone) . "</td></tr>";
	$body .= "<tr><td><strong>نوع المشاركة:</strong> </td><td>" . clean_string($type) . "</td></tr>";
	$body .= "</table>";
	$body .= "</body></html>";
	// ends

	$from = $name." <".$email_from.">";

	$to   = "missab <test@example.com>"; //  Email that you want to send message to...
	$subject = "New request ";
	
	// options for Google SMTP
	$host = "ssl://smtp.googlemail.com";
	$port = "465";
	$username = ""; // Put your real email address
	$password = "";     // Put your real email password here
		
	$headers = array ('From' => $email_from,
	  'To' => $to,
	  'Reply-To' => $email_from,
	  'Subject' => $subject,
	  'Content-Type' => 'text/html; charset=utf-8'
	);
	$smtp = Mail::factory('smtp',
	  array ('host' => $host,
	    'port' => $port,
	    'auth' => true,
	    'username' => $username,
	    'password' => $password));
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
	  echo json_encode(array('result' => 0, 'msg' => 'An error'));
    } else {
	  echo json_encode(array('result' => 1, 'msg' => 'Thanks!'));
	}
 
}
?>