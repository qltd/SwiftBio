<?
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

$referer = str_replace('?success=true', '', str_replace('?success=false', '', $_SERVER['HTTP_REFERER']));
$url = ($_POST['web2case'] == 1) ? 'https://www.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8' : 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';


$secret = "6LdyzQYUAAAAAI1_7NB6IU1Kh4QIGMZI8sONw3M-";
$response = $_POST["g-recaptcha-response"];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
$captcha_success=json_decode($verify);


if ($captcha_success->success==false) {
    //This user was not verified by recaptcha.
    // if ajax send a response otherwise redirect back to the page
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    } else {
        header('Location: ' . $referer . '?success=false');
    }

} elseif ($captcha_success->success==true) {
    //This user is verified by recaptcha send the form to salesforce
    //
    $fields = array(
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'company' => $_POST['company'],
        'street' => $_POST['street'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'salutation' => $_POST['salutation'],
        'title' => $_POST['title'],
        'URL' => $_POST['URL'],
        'phone' => $_POST['phone'],
        'mobile' => $_POST['mobile'],
        'fax' => $_POST['fax'],
        'description' => $_POST['description'],
        'zip' => $_POST['zip'],
        'country' => $_POST['country'],
        'lead_source' => $_POST['lead_source'],
        'industry' => $_POST['industry'],
        'rating' => $_POST['rating'],
        'revenue' => $_POST['revenue'],
        'employees' => $_POST['employees'],
        'Campaign_ID' => $_POST['Campaign_ID'],
        'emailOptOut' => $_POST['emailOptOut'],
        'faxOptOut' => $_POST['faxOptOut'],
        'doNoteCall' => $_POST['doNoteCall'],
        '00NE0000000Lrpa' => $_POST['00NE0000000Lrpa'],
        '00NE0000000Lrpu' => $_POST['00NE0000000Lrpu'],
        '00NE0000000Lrpz' => $_POST['00NE0000000Lrpz'],
        '00NE0000000Lrq4' => $_POST['00NE0000000Lrq4'],
        '00NE0000000Lrq9' => $_POST['00NE0000000Lrq9'],
        '00NE0000000LrqE' => $_POST['00NE0000000LrqE'],
        '00NE0000000LrqJ' => $_POST['00NE0000000LrqJ'],
        '00NE0000000LrqO' => $_POST['00NE0000000LrqO'],
        '00NE0000000LrqT' => $_POST['00NE0000000LrqT'],
        '00NE0000000Lrq0' => $_POST['00NE0000000Lrq0'],
        '00NE0000000LrqY' => $_POST['00NE0000000LrqY'],
        '00NE0000000Lzzc' => $_POST['00NE0000000Lzzc'],
        '00NE0000000Lzzh' => $_POST['00NE0000000Lzzh'],
        '00NE0000000Lzzm' => $_POST['00NE0000000Lzzm'],
        '00NE0000000Lzzr' => $_POST['00NE0000000Lzzr'],
        '00NE0000000Lzzw' => $_POST['00NE0000000Lzzw'],
        '00NE00000068QNu' => $_POST['00NE00000068QNu'],
        '00NE0000000M00B' => $_POST['00NE0000000M00B'],
        '00NE0000000M00G' => $_POST['00NE0000000M00G'],
        '00NE00000069Ark' => $_POST['00NE00000069Ark'],
        '00NE0000000M056' => $_POST['00NE0000000M056'],
        '00NE0000000M1un' => $_POST['00NE0000000M1un'],
        '00NE0000000M1us' => $_POST['00NE0000000M1us'],
        '00NE0000001sQLi' => $_POST['00NE0000001sQLi'],
        '00NE0000001sQMH' => $_POST['00NE0000001sQMH'],
        '00NE0000001sQMM' => $_POST['00NE0000001sQMM'],
        '00NE0000005K4ra' => $_POST['00NE0000005K4ra'],
        '00NE0000005K4rk' => $_POST['00NE0000005K4rk'],
        '00NE0000005K4rp' => $_POST['00NE0000005K4rp'],
        '00NE0000005K4xs' => $_POST['00NE0000005K4xs'],
        '00NE0000005K4xx' => $_POST['00NE0000005K4xx'],
        '00NE0000005K4y2' => $_POST['00NE0000005K4y2'],
        '00NE0000005K4y7' => $_POST['00NE0000005K4y7'],
        '00NE0000005KAte' => $_POST['00NE0000005KAte'],
        '00NE0000005KAtj' => $_POST['00NE0000005KAtj'],
        '00NE0000005KAtt' => $_POST['00NE0000005KAtt'],
        '00NE0000005KC0D' => $_POST['00NE0000005KC0D'],
        '00NE0000005KC0I' => $_POST['00NE0000005KC0I'],
        'oid' => $_POST['oid'],
        'retURL' => $referer . '?success=true',
        'member_status' => $_POST['member_status'],
    );


    // send a notification email to Swift for the Request Forms
    if ($_POST['oid'] == '00DE0000000KWb6' && $_POST['00NE00000069Ark'] == '1'){
        require_once("../inc/request-form-email.php");
    }


    //url-ify the data for the POST
    foreach($fields as $key=>$value) {
        $fields_string .= $key . '=' . encode_array($value).'&';
    }
    rtrim($fields_string, '&');

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);


    // send a response for ajax request
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo 'Valid';
    } else {
        header('Location: ' . $referer . '?success=true');
    }

}


