<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsEmailOrPhoneValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof IsEmailOrPhone) {
            throw new UnexpectedTypeException($constraint, IsEmailOrPhone::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/(\+7|8)[(\s-]*(\d)[\s-]*(\d)[\s-]*(\d)[)\s-]*(\d)[\s-]*(\d)[\s-]*(\d)[\s-]*(\d)[\s-]*(\d)[\s-]*(\d)[\s-]*(\d)/', $value) && !preg_match('/(\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}