<?php
namespace ZCFram;

/**
 *
 */
class Email
{
    use Hydrator;

    /**
     * The host name for the server smtp
     * @var string
     */
    protected $host;

    /**
     * The email address of where the mail is sent from
     * @var string
     */
    protected $from;

    /**
     * The email address where to send
     * @var string
     */
    protected $to;

    /**
     *
     * @var string
     */
    protected $serverName;

    /**
     * An instance of Flash message
     * @var object $flash
     */
    protected $flash;

    /**
     * An instance of Validator
     * @var object $Validator
     */
    protected $Validator;

    public function __construct(Flash $flash, Validator $Validator, Configurator $config)
    {
        // The Flash object is assigned to the variable $flash
        $this->flash = $flash;
        $this->validator = $Validator;

        // Hydrate the Email class
        $this->hydrate($config->getConfig('mail'));
    }

    /**
     * [validateAndSendEmail description]
     * @return Flash [description]
     */
    public function validateAndSendEmail():Flash
    {
        //Retrieving the class that validates the data sent
        $this->validator->required('name', 'text');
        $this->validator->required('email', 'email');
        $this->validator->required('comments', 'text');

        // If the validator does not return an error,
        // else adding error flash message
        if (!$this->validator->hasError()) {
            // Recovery of validated data
            $params = $this->validator->getParams();
            // Send the mail
            $this->sendEmail($params);
        } else {
            foreach ($this->validator->getError() as $key => $value) {
                $this->flash->addFlash('danger', $value);
            }
        }
        // Returns flash messages
        return $this->flash;
    }

    /**
     * Send an email
     * @param  array  $params [description]
     */
    private function sendEmail(array $params)
    {
        // Create the Transport
        $transport = (new \Swift_SmtpTransport($this->host, 25));

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

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
