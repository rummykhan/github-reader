<?php

namespace GithubReader\Github;

interface FileType
{
    const FILE = 'file';

    const DIRECTORY = 'dir';

    const SYMLINK = 'symlink';
}