<?php

error_reporting(-1);
require_once __DIR__ . '/../vendor/autoload.php';

define("to", 'yanivagent@gmail.com');
define("from", 'no-reply@afriat.info');
$erreur = "";
if ($_POST) {
    $valid = validate($_POST);
    $transport = (new Swift_SmtpTransport('auth.smtp.1and1.fr', 465, 'ssl'))
        ->setUsername(getenv('SMTP_LOGIN'))
        ->setPassword(getenv('SMTP_PASSWORD'))
    ;
    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance()
            ->setSubject("Message envoyé depuis le site : Diffusion Textile International")
            ->setFrom(array(from => 'Diffusion Textile International'))
            ->setTo(array(to))
            ->setBody(formatParamsHTML($_POST), 'text/html')
            ->addPart(formatParamsTXT($_POST), 'text/plain');
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
}

function validate($params) {
    $return = array();
    if (strlen($params['nom']) <= 0)
        $return['nom'] = true;
    if (filter_var($params['email'], FILTER_VALIDATE_EMAIL) == false)
        $return['email'] = true;
    if (strlen($params['message']) <= 0)
        $return['message'] = true;
    return $return;
}

function formatParamsHTML($params) {
    $message = "<html><head><head><body>";
    $message .= "<h2 style='padding:10px;'><u>Identitée :
        </u></h2><span>" . $params['nom'] . " / " . $params['compagny'] . "</span><br />";
    $message .= "<h2 style='padding:10px'><u>Email : 
        </u></h2><span>" . $params['email'] . "</span><br />";
    $message .= "<h2 style='padding:10px'><u>Téléphone : 
        </u></h2><span>" . $params['tel'] . "</span><br />";
    $message .= "<h2 style='padding:10px'><u>Société : 
        </u></h2><span>" . $params['compagny'] . "</span><br />";
    $message .= "<h2 style='padding:10px'><u>Message : 
        </u></h2><span>" . $params['message'] . "</span><br />";
    $message .= "<br><br><hr><br>";
    $message .= "Ce email a été envoyé via formulaire de contact http://dti.afriat.info</body></html>";
    return $message;
}

function formatParamsTXT($params) {
    $message = "Identitée : " . $params['nom'] . " / " . $params['compagny'];
    $message .= "Email : " . $params['email'];
    $message .= "Téléphone : " . $params['tel'];
    $message .= "Société : " . $params['compagny'];
    $message .= "Message : " . $params['message'];
    $message .= "------------------------------------------------------------------------------------------------";
    $message .= "Ce email a été envoyé via formulaire de contact http://dti.afriat.info";
    return $message;
}