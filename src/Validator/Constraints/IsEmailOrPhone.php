<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsEmailOrPhone extends Constraint
{
    public string $message = 'The string "{{ string }}" is neither an email nor a phone number';
//    public string $mode = 'strict';
//    public function validatedBy()
//    {
//        return $this::class.'Validator';
//    }

    public function __construct(string $mode = null, string $message = null, array $groups = null, $payload = null){
        parent::__construct([], $groups, $payload);

//        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }
}