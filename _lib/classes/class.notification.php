<?php

/**
 * @author Dan David
 */
include('class.phpmailer.php');

class notification
{
    /*     * *** Mailer **** */

    public $sendToEmail = '';
    public $sendToName = "<p>Hello, </p>";

    public $addReplyTo = "noreply@drims.org";
    public $addReplyToTemp = "noreply@drims.org"; //added for quick fix for spam issues 09.22.20
    public $from = "DRIMS TEAM";
    public $fromName = "DRIMS";
    public $fromEmail = "noreply@drims.org";
    public $fromEmailTemp = "noreply@drims.org"; //added for quick fix for spam issues 09.22.20

    public $broadCastSubject = '';
    public $broadCastMessage = '';

//    public $broadCastAttachment = '';
    public $broadCastAttachment = array();
    public $attachmentFileName = '';

    public $volunteer_info = '';

    //Add Agency
    public $agencyName;
    public $sentByName;
    public $sentByAgency;

    //Add Agency Contact
    public $contactName;

    //TEAMS
    public $teamName;
    public $teamArea;
    public $docTitle;


    /**
     * RESET password
     **/
    public $tempPassword = '';


    function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    public function initEmail()
    {
//        if ($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost') {
//            $this->mail->IsSMTP();
//        } else {
//            $this->mail->isSendMail();
//        }
        $this->mail->IsSMTP(); //local
//        $this->mail->isSendMail();
        $this->mail->SMTPDebug = 1;
        $this->mail->SMTPSecure = "tls";
        $this->mail->Host = "smtp.ionos.com";
        $this->mail->Port = '587';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "noreply@drims.org";
        $this->mail->Password = "DRIMS2018v2*";
        $this->mail->From = $this->fromEmailTemp;
        $this->mail->FromName = $this->fromName;
        $this->mail->AddReplyTo($this->addReplyToTemp);
        $this->mail->IsHTML(true);
//        $this->mail->AddAddress($this->sendToEmail);
    }

    public function sendEmail($subject)
    {

        try {

            $this->initEmail();

            switch ($subject) {

                case "volunteer_registration":
                    $this->mail->AddAddress($this->sendToEmail);

                    $this->mail->Subject = "Thank you for Volunteering!";
                    $this->mail->Body = $this->volunteer_registration();
                    break;

                case "send_broadcast":
                    $emails = explode(",", $this->sendToEmail);

                    //TODO REVERT TO BCC FOR LIVE
                    foreach ($emails as $val) {
                        $this->mail->AddBCC($val);
//                        $this->mail->AddAddress($val);
                    }

//                    ATTACHMENTS
                    if (!empty($this->broadCastAttachment['upload_attachment']['tmp_name'][0])) {
//                        $this->mail->AddAttachment($this->broadCastAttachment, $this->attachmentFileName);

                        //UPDATED to handle multiple attachments
                        $cnt = (count($this->broadCastAttachment['upload_attachment']['name']) - 1);
                        for ($i = 0; $i <= $cnt; $i++) {
                            $fileName = $this->broadCastAttachment['upload_attachment']['name'][$i];
                            $filePath = $this->broadCastAttachment['upload_attachment']['tmp_name'][$i];
                            $this->mail->AddAttachment($filePath, $fileName);

                        }
                    }

                    $this->mail->Subject = $this->broadCastSubject;
                    $this->mail->Body = $this->send_broadcast();
                    break;

//                case "agency_new_volunteer":
//                    $emails = explode(",",$this->sendToEmail);
//                    foreach($emails as $val) {
//                        $this->mail->AddBCC($val);
////                        $this->mail->AddAddress($val);
//                    }
//                    $this->mail->Subject = "BARR PORTAL - New Volunteer Request";
//                    $this->mail->Body = $this->agency_new_volunteer();
//                    break;


                case "reset_password":
                    $this->mail->AddAddress($this->sendToEmail);
                    $this->mail->Subject = "RESET PASSWORD";
                    $this->mail->Body = $this->reset_password();
                    break;

                case "add_agency":
                    $this->mail->AddAddress($this->sendToEmail);
                    $this->mail->Subject = "{$this->agencyName} added to DRIMS by {$this->sentByAgency}";
                    $this->mail->Body = $this->newAgency();
                    break;

                case "add_agency_user":
                    $this->mail->AddAddress($this->sendToEmail);
                    $this->mail->Subject = "You have been added to DRIMS by {$this->sentByAgency}";
                    $this->mail->Body = $this->NewAgencyUser();
                    break;

                case "team_notification":
                    $this->mail->AddAddress($this->sendToEmail);
                    $this->mail->Subject = "{$this->teamArea} added to Team {$this->teamName}";
                    $this->mail->Body = $this->TeamNotification();
                    break;

//                case "change_password":
//                    $this->mail->Subject = "Account Info Updated";
//                    $this->mail->Body = $this->change_password_body();
//                    break;

                default:
                    break;
            }

            $this->mail->Send();
        } catch (phpmailerException $e) {
            if (DEBUG) {
                echo $e->errorMessage();
                exit;
            }
        }
    }

    /**
     * EMAIL BODIES
     * */
    private function TeamNotification()
    {
        $body = $this->html_header();

        $body .= "<p>Hi " . $this->sendToName . ",</p>";
        $body .= "<p>";
        $body .= "A new {$this->teamArea} \"{$this->docTitle}\" has been added to team {$this->teamName} <br>";
        $body .= "by {$this->sentByName}";
        $body .= "</p>";
        $body .= $this->html_footer();

        return $body;
    }

    private function newAgency()
    {
        $body = $this->html_header();

        $body .= "<p>Dear " . $this->sendToName . ",</p>";
        $body .= "<p>Your organization {$this->agencyName} has been added to {$this->sentByAgency} Connection Portal powered by DRIMS.</p>";
        $body .= "<p>Requestor: {$this->sentByName} from {$this->sentByAgency}</p>";
        $body .= "<a href='" . ROOT_CMS_URL . "osignin?em=" . $this->sendToEmail . "'>" . ROOT_CMS_URL . "osignin?em=" . $this->sendToEmail . "</a><br>";
        $body .= "Password: " . $this->tempPassword;

        $body .= "<br><br>";

        $body .= $this->html_footer();

        return $body;
    }

    private function NewAgencyUser()
    {
        $body = $this->html_header();

        $body .= "<p>Dear " . $this->sendToName . ",</p>";
        $body .= "<p>You have been added you to {$this->agencyName} Connection Portal powered by DRIMS.</p>";
        $body .= "<p>Requestor: {$this->sentByName} from {$this->sentByAgency}</p>";
        $body .= "<a href='" . ROOT_CMS_URL . "osignin?em=" . $this->sendToEmail . "'>" . ROOT_CMS_URL . "osignin?em=" . $this->sendToEmail . "</a><br>";
        $body .= "Password: " . $this->tempPassword;

        $body .= "<br><br>";

        $body .= $this->html_footer();

        return $body;
    }

    private function agency_new_volunteer()
    {

        $body = $this->html_header();

        $body .= "<p>{$this->sendToName}</p>";
        $body .= "<p>The following individual has submitted a VOLUNTEER REQUEST:</p>";
        $body .= "<p>{$this->volunteer_info}</p>";
        $body .= "<p>Please review their information, if they are a match for a VOLUNTEER NEED you have please contact them as soon as possible</p>";

        $body .= "<br><br>";

        $body .= $this->html_footer();

        return $body;
    }

    private function send_broadcast()
    {

        $body = $this->html_header();

        $body .= "<p>{$this->sendToName}</p>";
        $body .= $this->broadCastMessage;

        $body .= "<br><br>";

        $body .= $this->html_footer();

        return $body;
    }


    private function volunteer_registration()
    {

        $body = $this->html_header();

        $body .= $this->sendToName;
        $body .= "<p><h3>Thank You for Volunteering!</h3></p>";
        $body .= "<p>Your request has been routed to each of our agencies.</p>";
        $body .= "<p>They will review your request and contact you directly if/as they have volunteer opportunities available.</p>";

        $body .= "<br><br>";

        $body .= $this->html_footer();

        return $body;
    }

    private function reset_password()
    {

        $body = $this->html_header();

        $body .= $this->sendToName;
        $body .= "<p>Here is your temporary password.</p>";
        $body .= "<p><strong>r31coV3rY</strong></p>";
        $body .= "<a href='" . MAIN_DIR . "signin" . "'>Click here to Login</a>";

        $body .= "<br><br>";

        $body .= $this->html_footer();

        return $body;
    }

    private function html_header()
    {
        $body = "<html><body style='background-color:#f0f0f0'>";
        $body .= "<table border='0' cellpadding='20' style='width:100%;margin-top:20;'>";
        $body .= "<tr><td align='center'>";
        $body .= "<table border='1' cellpadding='30' style='width:100%;font-size:14px;background-color:#ffffff'>";
        $body .= "<tr><td>";

        return $body;
    }

    private function html_footer()
    {
        $body = "<hr/>";
        $body .= "Sincerely,<br>";
        $body .= $this->from;
        $body .= "</td></tr></table>";
        $body .= "</td></tr>";
        $body .= "</table>";
        $body .= "</body></html>";

        return $body;
    }

}

?>