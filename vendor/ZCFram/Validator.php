<?php
namespace ZCFram;

/**
 * Form validation.
 */
class Validator
{

    /**
     * The list of fields.
     * @var array $field['name field' => 'content of the field']
     */
    private $field = [];

    /**
     * The list of the error
     * @var array $error['name field' => 'the error']
     */
    private $error = [];

    /**
     * This method is executed if the field is required
     * @param  string $value The name of the field
     * @param  string $type  The type of the variable (text, email, integer ...)
     */
    public function required(string $value, string $type)
    {
        if (empty($_POST[$value]) === true) {
            $this->setError([\ucfirst($value) => 'Le champ ' . $value . ' est requis']);
        } else {
            $this->check($value, $type);
        }
    }

    /**
     * Search and execute the method according to the type of the fields
     * @param  string $value  The name of the field
     * @param  string $type   The type of the variable (text, email, integer ...)
     */
    public function check(string $value, string $type)
    {
        $method = 'check'.ucfirst($type);

        if (!is_callable([$this, $method])) {
            throw new \BadFunctionCallException('La méthode utilisée n\'existe pas.');
        }

        $this->$method($value);
    }

    /**
     * Check if an email is valid
     * @param  string $email
     */
    protected function checkEmail(string $email)
    {
        if (filter_var($_POST[$email], FILTER_VALIDATE_EMAIL) !== false) {
            $this->setField([$email => $_POST[$email]]);
        } else {
            $this->setError(['Email' => 'Veuillez entrer une adresse email valide!']);
        }
    }

    /**
     * Sanitize an input type text
     * @param  string $text
     */
    protected function checkText(string $text)
    {
        $field = filter_input(INPUT_POST, $text, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->setField([$text => $field]);
    }

    /**
     * Check a password
     * @param  string $password
     */
    protected function checkPassword(string $password)
    {
        if (strlen($_POST[$password]) < 6) {
            $this->setError(['Password' => 'Mot de passe trop court!']);
        } else {
            $this->setField(['password' => $_POST[$password]]);
        }
    }

    /**
     * Check a password
     * @param  string $password
     */
    protected function checkInteger(string $value)
    {
        $_POST[$value] = (int)$_POST[$value];
        $this->setField([$value => $_POST[$value]]);
    }

    /**
     * Set the field variable
     * @param array $field
     */
    protected function setField(array $field)
    {
        if (!is_array($field)) {
            throw new \InvalidArgumentException('Les paramètres ne sont pas au bon format.');
        }

        $this->field = array_merge($this->field, $field);
    }

    /**
     * Set the error variable
     * @param array $error
     */
    protected function setError(array $error)
    {
        if (!is_array($error)) {
            throw new \InvalidArgumentException('Les paramètres ne sont pas au bon format.');
        }

        $this->error = array_merge($this->error, $error);
    }

    /**
     * Check if an error exists
     * @return boolean
     */
    public function hasError()
    {
        if (!empty($this->error)) {
            return true;
        }
        return false;
    }

    /**
     * Return the variable $field
     * @return array
     */
    public function getParams()
    {
        return $this->field;
    }

    /**
     * Return the variable $error
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }
}
