<?php

namespace GithubReader\Github;

use Exception;
use GithubReader\RepositoryReader;
use GithubReader\Encoding\Encoding;

/**
 * Class FileContent
 *  This Class Serve as a Content Holder of the file. User can use getContent() to get the contents of the file.
 *
 * @package GithubReader\Github
 */
final class FileContent extends Content
{
    protected $content;

    protected $encoding;

    public function __construct(RepositoryReader $reader, array $readable)
    {
        parent::__construct($reader, $readable);
    }

    protected function setContent($value)
    {
        $this->content = $value;
    }

    public function getContent()
    {
        switch ($this->encoding) {
            case Encoding::BASE64:
                return Encoding::base64ToPlain($this->content);
                break;
        }

        return $this->content;
    }

    protected function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    public function retrieve()
    {
        throw new Exception('This method is not required here.');
    }
}
