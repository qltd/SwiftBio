<?php
$referer = str_replace('?success=true', '', str_replace('?success=false', '', $_SERVER['HTTP_REFERER']));
$secret = "6LdyzQYUAAAAAI1_7NB6IU1Kh4QIGMZI8sONw3M-";
$response = $_POST["g-recaptcha-response"];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
$captcha_success=json_decode($verify);


if ($captcha_success->success==false) {
    //This user was not verified by recaptcha.
    // if ajax send a response otherwise redirect back to the page
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo 'Not valid';
    } else {
        header('Location: ' . $referer . '?success=false');
    }

} elseif ($captcha_success->success==true) {
    //This user is verified by recaptcha send the form to salesforce

    $url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
    $fields = array(
        'first_name' => urlencode($_POST['first_name']),
        'last_name' => urlencode($_POST['last_name']),
        'email' => urlencode($_POST['email']),
        'company' => urlencode($_POST['company']),
        'city' => urlencode($_POST['city']),
        'state' => urlencode($_POST['state']),
        'salutation' => urlencode($_POST['salutation']),
        'title' => urlencode($_POST['title']),
        'URL' => urlencode($_POST['URL']),
        'phone' => urlencode($_POST['phone']),
        'mobile' => urlencode($_POST['mobile']),
        'fax' => urlencode($_POST['fax']),
        'description' => urlencode($_POST['description']),
        'zip' => urlencode($_POST['zip']),
        'country' => urlencode($_POST['country']),
        'lead_source' => urlencode($_POST['lead_source']),
        'industry' => urlencode($_POST['industry']),
        'rating' => urlencode($_POST['rating']),
        'revenue' => urlencode($_POST['revenue']),
        'employees' => urlencode($_POST['employees']),
        'Campaign_ID' => urlencode($_POST['Campaign_ID']),
        'emailOptOut' => urlencode($_POST['emailOptOut']),
        'faxOptOut' => urlencode($_POST['faxOptOut']),
        'doNoteCall' => urlencode($_POST['doNoteCall']),
        '00NE0000000Lrpa' => urlencode($_POST['00NE0000000Lrpa']),
        '00NE0000000Lrpu' => urlencode($_POST['00NE0000000Lrpu']),
        '00NE0000000Lrpz' => urlencode($_POST['00NE0000000Lrpz']),
        '00NE0000000Lrq4' => urlencode($_POST['00NE0000000Lrq4']),
        '00NE0000000Lrq9' => urlencode($_POST['00NE0000000Lrq9']),
        '00NE0000000LrqE' => urlencode($_POST['00NE0000000LrqE']),
        '00NE0000000LrqJ' => urlencode($_POST['00NE0000000LrqJ']),
        '00NE0000000LrqO' => urlencode($_POST['00NE0000000LrqO']),
        '00NE0000000LrqT' => urlencode($_POST['00NE0000000LrqT']),
        '00NE0000000Lrq0' => urlencode($_POST['00NE0000000Lrq0']),
        '00NE0000000LrqY' => urlencode($_POST['00NE0000000LrqY']),
        '00NE0000000Lzzc' => urlencode($_POST['00NE0000000Lzzc']),
        '00NE0000000Lzzh' => urlencode($_POST['00NE0000000Lzzh']),
        '00NE0000000Lzzm' => urlencode($_POST['00NE0000000Lzzm']),
        '00NE0000000Lzzr' => urlencode($_POST['00NE0000000Lzzr']),
        '00NE0000000Lzzw' => urlencode($_POST['00NE0000000Lzzw']),
        '00NE00000068QNu' => urlencode($_POST['00NE00000068QNu']),
        '00NE0000000M00B' => urlencode($_POST['00NE0000000M00B']),
        '00NE0000000M00G' => urlencode($_POST['00NE0000000M00G']),
        '00NE00000069Ark' => urlencode($_POST['00NE00000069Ark']),
        '00NE0000000M056' => urlencode($_POST['00NE0000000M056']),
        '00NE0000000M1un' => urlencode($_POST['00NE0000000M1un']),
        '00NE0000000M1us' => urlencode($_POST['00NE0000000M1us']),
        '00NE0000001sQLi' => urlencode($_POST['00NE0000001sQLi']),
        '00NE0000001sQMH' => urlencode($_POST['00NE0000001sQMH']),
        '00NE0000001sQMM' => urlencode($_POST['00NE0000001sQMM']),
        '00NE0000005K4ra' => urlencode($_POST['00NE0000005K4ra']),
        '00NE0000005K4rk' => urlencode($_POST['00NE0000005K4rk']),
        '00NE0000005K4rp' => urlencode($_POST['00NE0000005K4rp']),
        '00NE0000005K4xs' => urlencode($_POST['00NE0000005K4xs']),
        '00NE0000005K4xx' => urlencode($_POST['00NE0000005K4xx']),
        '00NE0000005K4y2' => urlencode($_POST['00NE0000005K4y2']),
        '00NE0000005K4y7' => urlencode($_POST['00NE0000005K4y7']),
        '00NE0000005KAte' => urlencode($_POST['00NE0000005KAte']),
        '00NE0000005KAtj' => urlencode($_POST['00NE0000005KAtj']),
        '00NE0000005KAtt' => urlencode($_POST['00NE0000005KAtt']),
        '00NE0000005KC0D' => urlencode($_POST['00NE0000005KC0D']),
        '00NE0000005KC0I' => urlencode($_POST['00NE0000005KC0I']),
        'oid' => urlencode($_POST['oid']),
        'retURL' => urlencode($referer . '?success=true'),
        'member_status' => urlencode($_POST['member_status']),
    );

    //url-ify the data for the POST
    foreach($fields as $key=>$value) {
        $fields_string .= $key.'='.$value.'&';
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


