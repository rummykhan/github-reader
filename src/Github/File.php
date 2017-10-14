<?php

namespace GithubReader\Github;

final class File extends Content
{
    public function retrieve()
    {
        return new FileContent($this->reader, $this->reader->readPath($this->getPath()));
    }
}