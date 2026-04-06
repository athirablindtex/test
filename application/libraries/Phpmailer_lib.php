<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Phpmailer_lib
{
    public function load()
    {
        return new PHPMailer(true);
    }
}
