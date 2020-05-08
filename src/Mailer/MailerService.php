<?php
namespace App\Mailer;

use Doctrine\ORM\EntityManagerInterface; 
use Psr\Log\LoggerInterface;
use Twig\Environment;

abstract class MailerService
{
    protected $logger;
    protected $em;
    protected $swiftMailer;
    protected $templating;

    // Email properties
    protected $subject;
    protected $emailView;
    protected $recipients;
    protected $fromEmail;
    protected $userFrom;
    protected $vars;
   
    // Notification properties
    protected $notificationSubject;
    protected $notificationMessage;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, \Swift_Mailer $swiftMailer, Environment $templating)
    {
        $this->logger = $logger;
        $this->em = $entityManager;
        $this->swiftMailer = $swiftMailer;
        $this->templating = $templating;

        // Default email properties
        $this->recipients = array();
        $this->vars = array();
        $this->fromEmail = getenv('EMAIL_FROM');
        $this->userFrom = null;
        $this->subject = "Void Subject";
        
        // Default notification properties
        $this->notificationSubject = "";
        $this->notificationMessage = "";

        return $this;
    }

    abstract protected function config();

    public function setEmailView($filename) {
        $this->emailView = $filename;
        return $this;
    }

    public function setRecipients($users = array()) {
        $this->recipients = $users;
        return $this;
    }

    public function setFromEmail($email) {
        $this->fromEmail = $email;
        return $this;
    }

    public function setUserFrom($user) {
        $this->userFrom = $user;
        return $this;
    }

    public function setVars($vars = array()) {
        $this->vars = $vars;
        return $this;
    }

    public function getVar($varName) {
        if(!array_key_exists($varName, $this->vars)) {
            throw new \Exception("Var $varName is not defined");
        }
        return $this->vars[$varName];
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function setNotificationSubject($notificationSubject) {
        $this->notificationSubject = $notificationSubject;
        return $this;
    }

    public function setNotificationMessage($notificationMessage) {
        $this->notificationMessage = $notificationMessage;
        return $this;
    }

    public function send()
    {
        $this->config();
        
        foreach ($this->recipients as $recipient) {
            $this->vars['recipient'] = $recipient;
            $this->vars['frontendURL'] = getenv('FRONTEND_URL');
            
            $message = (new \Swift_Message($this->subject))
                ->setFrom($this->fromEmail)
                ->setTo($recipient)
                ->setBody(
                    $this->templating->render(
                        'emails/' . $this->emailView,
                        $this->vars
                    ),
                    'text/html'
                )
            ;

            if(!getenv("MAILER_DISABLED")) {
                $this->swiftMailer->send($message);
            }
        }
    }

}