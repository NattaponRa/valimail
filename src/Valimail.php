<?php

namespace Valimail;

use Valimail\ValidationMethod\RFC_Validation;
use Valimail\ValidationMethod\SMTP_Validation;
use Valimail\ValidationMethod\Syntax_Vlidation;

class Valimail
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function validateAllMethod()
    {
        if ($this->validateRFCStandard()) {
            $validation["validateRFCStandard"] = array("status" => true, "messages" => 'Success');
        } else {
            $validation["validateRFCStandard"] = array("status" => false, "messages" => 'InvalidRFCStandard');
        }


        $validation["validateSMTP"] = $this->validateSMTP();

        $countTrue = 0;
        foreach ($validation as $method) {
            if ($method["status"]) {
                $countTrue++;
            }
        }
        $status = count($validation) == $countTrue;

        return array($status, $validation);
    }

    public function validateRFCStandard()
    {
        $rfc = new RFC_Validation();
        return $rfc->validateRfcStandard($this->email);
    }

    public function validateMXRecord()
    {

        list($name, $domain) = explode('@', $this->email);

        if (!checkdnsrr($domain, 'MX')) {
            return false;
        } else {
            return true;
        }

    }

    public function validateSMTP()
    {
        $smtp = new SMTP_Validation();
        return $smtp->SMTPValidate($this->email);
    }

    public function validateSyntax()
    {
        $syntax = new Syntax_Vlidation();
        return $syntax->syntaxValidate($this->email);
    }


}


