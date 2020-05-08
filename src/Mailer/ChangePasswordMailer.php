<?php

namespace App\Mailer;

class ChangePasswordMailer extends MailerService
{
    public function config()
    {
        // Email config
        $this->setSubject("Password Changed!");
        $this->setEmailView("changePasswordEmail.html.twig");
    }
}