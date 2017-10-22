<?php

error_reporting(-1);
include_once '../lib/swift_required.php';
define("to", 'yaniv.afriat@gmail.com');
define("from", 'no-reply@dti.ma');
$erreur = "";
$message = "<html>
    <head>
        <title>Press Agency Online</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    </head>
    <body>
        <div style='width: 100%; background-color: #F39200'>
            <div style='width: 70%; margin-left: auto; margin-right: auto; text-align: center'>
                <a href='http://pressagencyonline.com'><img alt='Happy New Year 2014' src='http://dti.ma/boule.gif' style='width: 100%'></a>
                <br>
                <div style='margin-left: auto; margin-right: auto; width:50%'>
                    <a href='http://pressagencyonline.com'><img alt='logo' src='http://dti.ma/logonew.jpg' style='width: 50%; float: left'></a>
                    <div style='width: 48%; padding-left: 2%; float: left; font-size: 14px; text-align: left'>
                    <br>
                        <a href='mailto:contact@pressagencyonline.com'>contact@pressagencyonline.com</a><br>
                        36 rue Etienne Marcel<br>
                        75002 Paris
                    </div>
                </div>
            </div>
            <div style='clear: both'></div>
        </div>
    </body>
</html>";
$transport = Swift_MailTransport::newInstance();
$mailer = Swift_Mailer::newInstance($transport);

$message = Swift_Message::newInstance()
        ->setSubject("Message envoyé depuis le site : Diffusion Textile International")
        ->setFrom(array(from => 'Diffusion Textile International'))
        ->setTo(array(to, "michael@mynetworksolution.fr"))
        ->setBody($message, 'text/html');
$message->setPriority(2);
$failures = array();
if (!$mailer->send($message, $failures)) {
    echo "Failures:";
    var_dump($failures);
} else {
    //header("Location: ../");
    $success = true;
    return $success;
}