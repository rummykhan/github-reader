<?php

namespace GithubReader\Github;

/**
 * Class File
 *  This class extends the Content Method,
 *  Since this is a file and file can only have contents, so the only difference is of retrieve mehtod.
 *
 * @package GithubReader\Github
 */
final class File extends Content
{
    public function retrieve()
    {
        return new FileContent($this->reader, $this->reader->readPath($this->getPath()));
    }
}