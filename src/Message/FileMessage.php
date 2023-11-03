<?php

namespace App\Message;

class FileMessage
{
    public function __construct(
        private readonly string $file,
    ){}

    public function getFile(){
        return $this->file;
    }
}