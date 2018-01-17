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
     * @return string         [description]
     */
    public function hash(array $params): string
    {
        
        $length = strlen($params['password']);
        $salt = sha1($params['email']);
        return  hash('sha256', $length.$salt.$params['password']);

        //return $params['password'];
    }
}
