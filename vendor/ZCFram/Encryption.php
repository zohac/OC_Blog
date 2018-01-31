<?php
namespace ZCFram;

/**
 * password hashing
 */
class Encryption
{

    /**
     * password hashing
     * @param  array  $params [email, password]
     * @return string         hash in sha256 of the password
     */
    public function hash(array $params): string
    {
        // We recover the password size,
        // the sha1 of the email to get a dynamic salt,
        // and we hash the whole thing in sha256
        $length = strlen($params['password']);
        $salt = sha1($params['email']);
        return  hash('sha256', $length.$salt.$params['password']);
    }
}
