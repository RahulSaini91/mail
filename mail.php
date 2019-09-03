<?php  error_reporting(E_ALL); session_start(); date_default_timezone_set('Asia/Kolkata');

/*
 ******************************************
 ******** MAKE CHANGES AS YOU WANT ********
 ******************************************
 */
$mail = array();
$mail['to'] 		= 'receiver@example.com'; //replace with your email, multiple email id seperated by comma
$mail['from_name'] 	= 'Sender Name/Website Name'; //replace with yours
$mail['from_mail'] 	= 'receiver@example.com'; //replace with yours
$mail['subject'] 	= "A New Contact Message Received";
$mail['message'] 	= '
<p><b>Details of sender:</b></p>

{fields}

- Thanks &amp; Regards

';


/*
 ******************************************
 ******** DON'T MODIFY CODE BELOW *********
 ******************************************
 */
/* Mail headers */
$mail['headers'][] = "MIME-Version: 1.0" . "\r\n";
$mail['headers'][] = "Content-type:text/html;charset=UTF-8" . "\r\n";
$mail['headers'][] = "From: ".$mail['from_name']." <".$mail['from_mail'].">"."\r\n";
$mail['headers'] = join('',$mail['headers']);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $field = array();
	foreach($_POST as $key => $value){
		$key = htmlentities(trim(utf8_encode($key)));
		$value = htmlentities(trim(utf8_encode($value)));
		$field[$key] = $value;
	}
if(!$field['name']){
   exit("Name is Required");
}
if(!$field['email']){
   exit("Email is Required");
}
if(!filter_var($field['email'],FILTER_VALIDATE_EMAIL)){
   exit("Invalid Email");
}

function send_mail($fields){
    global $mail;
    $m = '<html><head><title>'.$mail['subject'].'</title></head><body><table>';
    if(is_array($fields) && count($fields) > 0){
        foreach($fields as $key => $value){
            if(!$value){
                continue;
            }
            $m .= '
            <tr>
                <th align="left">'.ucwords(str_replace(array('-','-'),' ',$key)).'</th>
                <th>:</th>
                <td>'.nl2br($value).'</td>
            </tr>
            ';
        }
    }
	$m .= '</table></body></html>';
	$mail['message'] = str_replace('{fields}',$m,$mail['message']);
	$to = explode(",",$mail['to']);
	$status = 0;
	foreach($to as $_to){
		$_to = strtolower($_to);
		if(!filter_var($_to,FILTER_VALIDATE_EMAIL)){
			continue;
		}
		//require_once 'PHPMailer.php'; /* COMING SOON */
		if(mail($_to,$mail['subject'],$mail['message'],$mail['headers'],'-f'.$mail['from_mail'])){
			$status = 1;
		}else{
		    $status = 'Unable to send your message at this time, please try agian later';
		}		
	}
	return $status;
}