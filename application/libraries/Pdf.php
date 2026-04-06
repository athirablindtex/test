<?php

use Dompdf\Dompdf;
use Mpdf\Mpdf;


defined('BASEPATH') or exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/dompdf/autoload.inc.php');

use Dompdf\Options;

class Pdf extends Dompdf
{
    function createPDF($html, $filename = '', $path = '', $download = false, $paper = 'A4', $orientation = 'portrait')
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        
        $dompdf->load_html($html);
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
        file_put_contents($path . $filename . '.pdf', $dompdf->output());
        return $filename;
    }
     public function createPDF_mpdf($html, $file_name, $path , $footer_html = '')
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);
    $mpdf->SetHTMLFooter('
<div style="text-align:right; font-size:9px; color:#999;">
    Page {PAGENO} of {nbpg}
</div>
');

        $mpdf->WriteHTML($html);

        $file = $path . $file_name . '.pdf';
        $mpdf->Output($file, \Mpdf\Output\Destination::FILE);



        return $file_name;
    }

    function downloadPDF($html, $filename = '', $path = '', $download = false, $paper = 'A4', $orientation = 'portrait')
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->load_html($html);
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
        if ($download)
            $dompdf->stream($filename . '.pdf', array('Attachment' => 1));
        else
            $dompdf->stream($filename . '.pdf', array('Attachment' => 0));
    }
}
