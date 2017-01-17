<?php

// load WP
require_once("../../../../wp-load.php");


// format the arrays into comma separated lists
$formatted = false;
foreach($fields as $key => $value) {
    if (is_array($value)){
        $formatted .= '<tr><td>' . $key . '</td><td>' . implode(', ', $value) . '</td></tr>';
    } else {
         $formatted .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
    }
}

// HTML EMAIL TEMPLATE  $message
ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
        <title><?php echo get_bloginfo('name'); ?></title>
    </head>
    <body marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <div id="wrapper" style="background-color:#f5f5f5;margin:0;padding:70px 0 70px 0;width:100%">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:3px!important">
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Header -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="background-color:#962878;border-radius:3px 3px 0 0!important;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif">
                                        <tr>
                                            <td id="header_wrapper" style="padding:36px 48px;display:block">
                                                <h1 style="color:#ffffff;font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left">Request Form</h1>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Header -->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Body -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                        <tr>
                                            <td valign="top" id="body_content">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div id="body_content_inner">
                                                                <table class="m_-8716659537653747656td" cellspacing="0" cellpadding="6" style="width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#79797b;border:1px solid #e4e4e4" border="1">
                                                                   <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Name: </strong>
                                                                            <?php echo $fields['first_name'] . ' ' . $fields['last_name']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Institution: </strong>
                                                                            <?php echo $fields['company']; ?>
                                                                        </td>
                                                                    </tr>
                                                                   <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Address: </strong>
                                                                            <?php echo $fields['street']; ?>
                                                                        </td>
                                                                    </tr>
                                                                   <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>City: </strong>
                                                                            <?php echo $fields['city']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>State: </strong>
                                                                            <?php echo $fields['state']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Zip: </strong>
                                                                            <?php echo $fields['zip']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Country: </strong>
                                                                            <?php echo $fields['country']; ?>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Email: </strong>
                                                                            <?php echo $fields['email']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Phone: </strong>
                                                                            <?php echo $fields['phone']; ?>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Area(s) of Interest: </strong>
                                                                            <?php echo implode(', ', $fields['00NE0000005K4rp']); ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Number of NGS library preps: </strong>
                                                                            <?php echo $fields['00NE0000005K4xx']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Product Sample Requests: </strong>
                                                                            <?php echo implode(', ', $fields['00NE0000005K4y2']); ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>Comments: </strong>
                                                                            <?php echo $fields['00NE0000005K4xs']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#79797b;padding:12px">
                                                                            <strong>How did you hear about Swift: </strong>
                                                                            <?php echo implode(', ', $fields['lead_source']); ?>
                                                                        </td>
                                                                    </tr>

                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Footer -->
                                    <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
                                        <tr>
                                            <td valign="top">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td colspan="2" valign="middle" id="credit" style="padding:0 48px 48px 48px;border:0;color:#c07eae;font-family:Arial;font-size:12px;line-height:125%;text-align:center">
                                                            <?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Footer -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>



<?php
$message = ob_get_clean();


$to = 'techsupport@swiftbiosci.com';
$subject = 'Request Form Submission';
$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . $fields['first_name'] . ' ' . $fields['last_name'] . ' <' . $fields['email'] . '>');

wp_mail( $to, $subject, $message, $headers );

return $return;
