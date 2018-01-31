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
        // If the required field is empty, an exception is raised.
        if (empty($_POST[$value]) === true) {
            $this->setError([\ucfirst($value) => 'Le champ ' . $value . ' est requis']);
        } else {
            // Otherwise we check the value
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
        // The method name is formatted.
        $method = 'check'.ucfirst($type);
        
        // If the method is not executable, an exception is raised.
        if (!is_callable([$this, $method])) {
            throw new \BadFunctionCallException('La méthode utilisée n\'existe pas.');
        }
        // The method is executed
        $this->$method($value);
    }

    /**
     * Check if an email is valid
     * @param  string $email
     */
    protected function checkEmail(string $email)
    {
        // We record the field if it is a valid email,
        // otherwise we record an error.
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
        // We clean up the field, and we record it.
        $field = filter_input(INPUT_POST, $text, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->setField([$text => $field]);
    }

    /**
     * Check a password
     * @param  string $password
     */
    protected function checkPassword(string $password)
    {
        // We're just checking the length of the field.
        // We record it, otherwise we record an error.
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
        // We make sure that the field is an integer.
        // And we record it
        $_POST[$value] = (int)$_POST[$value];
        $this->setField([$value => $_POST[$value]]);
    }

    /**
     * Set the field variable
     * @param array $field
     */
    protected function setField(array $field)
    {
        // We verify that the variable is a table, otherwise we raise an exception.
        if (!is_array($field)) {
            throw new \InvalidArgumentException('Les paramètres ne sont pas au bon format.');
        }
        // The new field is saved in the table containing all validated fields.
        $this->field = array_merge($this->field, $field);
    }

    /**
     * Set the error variable
     * @param array $error
     */
    protected function setError(array $error)
    {
        // We verify that the variable is a table, otherwise we raise an exception.
        if (!is_array($error)) {
            throw new \InvalidArgumentException('Les paramètres ne sont pas au bon format.');
        }
        // The new field is stored in the table containing all incorrect fields.
        $this->error = array_merge($this->error, $error);
    }

    /**
     * Check if an error exists
     * @return boolean
     */
    public function hasError()
    {
        // If an error has been recorded, we return true, else false.
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
        // Return the table containing all validated fields.
        return $this->field;
    }

    /**
     * Return the variable $error
     * @return array
     */
    public function getError()
    {
        // Return the table containing all incorrect fields.
        return $this->error;
    }
}
