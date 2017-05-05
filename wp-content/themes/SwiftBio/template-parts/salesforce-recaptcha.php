<?
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


// URL encode arrays if they are an array
function encode_array($value){
    if(is_array($value))
    {
      $return = urlencode(serialize($value));
    } else {
      $return = urlencode("$value");
    }
    return $return;
}
require_once("../../../../wp-load.php");

$referer = (isset($_SERVER['HTTP_REFERER'])) ? str_replace('?success=true', '', str_replace('?success=false', '', $_SERVER['HTTP_REFERER'])) : 'https://' . $_SERVER['SERVER_NAME'];
$url = (isset($_POST['web2case']) && $_POST['web2case'] == 1) ? 'https://salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8' : 'https://salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';

$secret = "6LdyzQYUAAAAAI1_7NB6IU1Kh4QIGMZI8sONw3M-";
$response = (isset($_POST["g-recaptcha-response"])) ? $_POST["g-recaptcha-response"] : '';
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
$captcha_response = json_decode($verify);
$captcha_success = ($captcha_response->success == true) ? true : false;


if ($captcha_success == false) {

    //This user was not verified by recaptcha.
    // if ajax send a response otherwise redirect back to the page
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    } else {
        header('Location: ' . $referer . '?success=false');
    }

} elseif ($captcha_success == true) {
    //This user is verified by recaptcha send the form to salesforce

    /* Clean up Post data */
    $fields = array();
    foreach ($_POST as $k => $v){
        $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8');
        $v = htmlentities($v, ENT_QUOTES, 'UTF-8');
        $fields[$k] = $v;
    }

    /* Set the return url to go back to the form they came from */
    if ($referer != ''){
         $fields['retURL'] = $referer . '?success=true';
    }

    // send a notification email to Swift for the Request Forms
    // disabled for now until we can get a salesforce direct link in it
    // if ($fields['oid'] == '00DE0000000KWb6' && $fields['00NE00000069Ark'] == '1'){
    //     require_once("../inc/request-form-email.php");
    // }
    //

    //url-ify the data for the POST
    $fields_string = false;
    foreach($fields as $key=>$value) {
        if (is_array($value) && !empty($value)){
            $fields_string .= $key . '=' . encode_array(implode(', ', $value)).'&';
        } else {
            if (!empty($value)){
                $fields_string .= $key . '=' . encode_array($value).'&';
            }
        }
    }
    $fields_string = rtrim($fields_string, '&');

    //update_post_meta('1', 'curl_string', $fields_string);

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION, TRUE);

    //execute post
    $result = curl_exec($ch);
    if ($result !== false) {
        //echo $return;
        $valid = true;
    }

    if($errno = curl_errno($ch)) {
        $error_message = curl_strerror($errno);
        //echo "cURL error ({$errno}):\n {$error_message}";
    }

    //close connection
    curl_close($ch);

    // send a response for ajax request
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if ($valid === true){
            echo 'Valid';
        }
    } else {
        if ($valid === true){
            header('Location: ' . $referer . '?success=true');
        } else {
            header('Location: ' . $referer . '?success=false');
        }
    }

}


