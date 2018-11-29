<?php

namespace Ximdex\Traits;

trait Tokenizer
{
    protected static function generateToken($length = 60)
    {
        try {
            $token = bin2hex(random_bytes($length / 2));
        } catch (\Exception $ex) {
            $token = md5(uniqid(rand(), true));
        }
        return $token;
    }
}