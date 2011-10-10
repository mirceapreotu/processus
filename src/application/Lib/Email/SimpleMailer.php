<?php
/**
 * Lib_Email_SimpleMailer Class
 *
 * @package Lib_Email
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Email_SimpleMailer
 *
 *
 * @package Lib_Email
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Email_SimpleMailer
{

    /**
     * Example:
     *   array(
     *       array('sebastian.schmidt@meetidaaa.de'),
     *       array('sebastian.schmidt@meetidaaa.de','seb'),
     *       array('foo@bar.de','foo'),
     *       'me@world.de'
     *   )
     *
     *  or
     *
     *  'me@world.de'
     *
     * @var null|array|string
     */
    public $to = null;

    /**
     *  Example:
     *       array('sebastian.schmidt@meetidaaa.de','seb')
     *
     *      or
     *
     *      'sebastian.schmidt@meetidaaa.de'
     *
     *
     * @var null|array|string
     */
	public $from = null;
    /**
     *  Example:
     *      array(
     *          array('sebastian.schmidt@meetidaaa.de','seb'),
     *          "foo@bar.de"
     *      )
     *
     *  or
     *      "me@wold.de"
     *
     * @var null|array|string
     */
	public $replyTo = null;

    /**
     * Example:
     * 'Test htmlMail';
     * @var null|string
     */
	public $subject = null;
    /**
     * Example:
     * "Hello World mit Ümläuten";
     * @var string
     */
	public $text="";

    /**
     * Example:
     * "<b>Hello World</b> mit Ümläuten"
     * @var null|string
     */
	public $htmlText=null;


    

    /**
     * @param  string $email
     * @param bool $checkDNS
     * @return bool
     */
	public function isValidEmail($email,$checkDNS = true)
    {
        $result = (bool)(Lib_Utils_Email::isValidAddress(
            $email,
            (bool)$checkDNS
        )===true);

        if ($result !== true) {
            return $result;
        }

        $mail = new PHPMailerLite(false);
        $isValid = $mail->ValidateAddress($email);
        if ($isValid !== true) {
            return false;
        }

        return $result;
    }


    /**
     * @return bool
     */
    public function send()
	{
		$mail             = new PHPMailerLite(true);
		// defaults to using php "Sendmail"
        // (or Qmail, depending on availability)

		$mail->IsMail(); // telling the class to use native PHP mail()
		$mail->CharSet           = 'UTF-8';//'iso-8859-1';


        /*
		  $mail->SetFrom('name@yourdomain.com', 'First Last');
		  $mail->AddAddress('whoto@otherdomain.com', 'John Doe');
		  $mail->Subject = 'PHPMailer Test Subject via mail(), advanced';
		  $mail->AltBody = 'To view the message,'
                .' please use an HTML compatible email viewer!';
         // optional - MsgHTML will create an alternate automatically
		  $mail->MsgHTML(file_get_contents('contents.html'));
		  $mail->AddAttachment('images/phpmailer.gif');      // attachment
		  $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
        */

        // from
        if (is_array($this->from)) {
            $mail->SetFrom(
                            Lib_Utils_Array::getProperty($this->from, 0),
                            Lib_Utils_Array::getProperty($this->from, 1)
                            );
        } else {
     
            if (Lib_Utils_String::isEmpty($this->from) !== true) {
                $mail->SetFrom($this->from);
            }

        }
        // to
        if (is_array($this->to)) {

            foreach($this->to as $to) {

                if (is_array($to)) {

                    /*
                    $mail->AddAddress(
                        Lib_Utils_Array::getProperty($to, 0),
                        Lib_Utils_Array::getProperty($to, 1)
                    );
                    */
                    $mail->AddAddress(
                        Lib_Utils_Array::getProperty($to, 0)

                    );


                } else {

                    $mail->AddAddress($to);
                }
            }

        } else {

            $mail->AddAddress($this->to);
        }

        if (is_array($this->replyTo)) {
            foreach($this->replyTo as $replyTo) {

                if (is_array($replyTo)) {
                    /*
                    $mail->AddReplyTo(
                        Lib_Utils_Array::getProperty($replyTo, 0),
                        Lib_Utils_Array::getProperty($replyTo, 1)
                    );
                    */
                    $mail->AddReplyTo(
                        Lib_Utils_Array::getProperty($replyTo, 0)
                    );




                } else {
                    $mail->AddReplyTo($replyTo);
                }
            }
        } else {
            if ($this->replyTo !== null) {
                $mail->AddReplyTo($this->replyTo);
            }
        }




        // subject
        if ($this->subject !== null) {
            $mail->Subject = $this->subject;
        }

        // plain text
        if ($this->text !== null) {
            $mail->AltBody = $this->text;
        }


        // htmlText
        if ($this->htmlText !== null) {
            $mail->MsgHTML($this->htmlText);
        }
        if (Lib_utils_String::isEmpty($this->htmlText)) {
            $mail->MsgHTML($this->text);
        }


        //var_dump($mail->From);
        //var_dump($mail->FromName);
        // send now
        $result = (bool)$mail->Send();
        return $result;
	}
	
}