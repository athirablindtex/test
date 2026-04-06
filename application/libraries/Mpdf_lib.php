<?php
use Mpdf\Mpdf;

class Mpdf_lib extends Mpdf
{
    public function __construct()
    {
        parent::__construct([
            'tempDir' => sys_get_temp_dir(), // for shared hosting
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);
    }
}
