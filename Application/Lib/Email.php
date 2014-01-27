<?php

namespace Application\Lib;

class Email extends \PHPMailer {
    /**
     * The constructor
     */
    public function __construct() {
        $this->isSMTP();
        $this->SMTPAuth   = true;
        $this->SMTPSecure = 'tls';
        $this->Host       = 'smtp.gmail.com';
        $this->Username   = 'topcoasters@gmail.com';
        $this->Password   = 'dchaepacqlzosxsl';
        $this->Port       = 587;
    }

    /**
     * Sends an email
     */
    public function sendIt($toWho, $toAddress, $subject, $message, $fromWho='', $fromAddress='') {
        $fromWho     = $fromWho ?: 'Top Coasters';
        $fromAddress = $fromAddress ?: 'topcoasters@gmail.com';

        $this->setFrom('topcoasters@gmail.com', 'Top Coasters');
        $this->addAddress($toAddress, $toWho);

        if($fromWho && $fromAddress) {
            $this->addReplyTo($fromAddress, $fromWho);
        }
        
        $this->Subject = $subject . ' - Top Coasters';

        $this->MsgHTML($message);

        return $this->send();
    }
}