<?php

namespace GithubReader\Encoding;

abstract class Encoding
{
    const BASE64 = 'base64';

    public static function base64ToPlain($content, $strict = true)
    {
        return base64_decode($content, $strict);
    }
}