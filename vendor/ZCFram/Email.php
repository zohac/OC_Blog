<?php
namespace ZCFram;

/**
 *
 */
class Email
{
    use Hydrator;

    /**
     * [protected description]
     * @var [type]
     */
    protected $host;

    protected $from;

    protected $to;

    protected $serverName;

    protected $flash;

    public function __construct()
    {
        // Get configuration
        $mail = Container::getConfigurator('mail');

        $this->hydrate($mail);

        $this->flash = new Flash;
    }

    /**
     * [validateAndSendEmail description]
     * @return string [description]
     */
    public function validateAndSendEmail():array
    {
        //Retrieving the class that validates the data sent
        $Validator = Container::getValidator();
        $Validator->required('name', 'text');
        $Validator->required('email', 'email');
        $Validator->required('comments', 'text');

        // If the validator does not return an error,
        // else adding error flash message
        if (!$Validator->hasError()) {
            // Recovery of validated data
            $params = $Validator->getParams();
            // Send the mail
            $this->sendEmail($params);

        } else {
            foreach ($Validator->getError() as $key => $value) {
                $this->flash->addFlash('danger', $value);
            }
        }
        // Returns flash messages
        return $this->flash->getFlash();
    }

    /**
     * * Send an email
     * @param  array  $params [description]
     */
    private function sendEmail(array $params)
    {
        $mailer = $this->getMailer();
        $message = $this->getSwiftMessage();

        // Create the message
        $message->setBody('
            De : '.$params['name'].'
            Email : '.$params['email'].'
            Content : '.$params['comments']);

        // Send the message
        $result = $mailer->send($message);

        // Adding a flash message if successful or unsuccessful
        if ($result > 0) {
            $this->flash->addFlash('success', 'E-mail envoyé avec succès. Merci '.$params['name'].'!');
        } else {
            $this->flash->addFlash('danger', 'Une erreur est survenu lors de l\'envoi de mail.');
        }
    }

    /**
     * Return an instance of Swift_Mailer
     * @return object Swift_Mailer
     */
    public function getMailer()
    {
        // Create the Transport
        $transport = (new \Swift_SmtpTransport($this->host, 25));

        // Create the Mailer using your created Transport
        return new \Swift_Mailer($transport);
    }

    /**
     * Return an instance of Swift_Message
     * @return object Swift_Message
     */
    public function getSwiftMessage()
    {
        // Recovery of a DKIM private key, to secure the mails.
        //$privatekey = \file_get_contents(__DIR__.$mail['dkimPath']);
        // Creating a signature by SwiftMailer
        //$signer = new \Swift_Signers_DKIMSigner($privatekey, $mail['serverName'], 'default');

        // Creating the message header
        $message =  new \Swift_Message();
        //$message->attachSigner($signer);
        $message
            ->setSubject('Demande de contact')
            ->setFrom([$this->from => $this->serverName])
            ->setTo([$this->to => $this->serverName]);
        // Returns the prepared message
        return $message;
    }

    /**
     * [setHost description]
     * @param string $host [description]
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * [setHost description]
     * @param string $from [description]
     */
    public function setFrom(string $from)
    {
        $this->from = $from;
    }

    /**
     * [setHost description]
     * @param string $to [description]
     */
    public function setTo(string $to)
    {
        $this->to = $to;
    }

    /**
     * [setHost description]
     * @param string $host [description]
     */
    public function setServerName(string $serverName)
    {
        $this->serverName = $serverName;
    }
}
