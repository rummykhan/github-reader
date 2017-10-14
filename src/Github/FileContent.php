<?php

namespace GithubReader\Github;

use GithubReader\RepositoryReader;

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
}
