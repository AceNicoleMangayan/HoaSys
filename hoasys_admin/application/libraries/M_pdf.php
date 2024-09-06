<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pdf
{
    function __construct()
    {
        include_once APPPATH . 'third_party/mpdf_vendor/autoload.php';
    }
    function pdf()
    {
        $CI = &get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
    function load($param = [])
    {
        try {
            return new \Mpdf\Mpdf([
                'orientation' => 'P',
                'format' =>'Letter',//A4
                'tempDir' => __DIR__ . '/mpdf_temp', // uses the current directory's parent "tmp" subfolder
                'setAutoTopMargin' => 'stretch',//pad
                'setAutoBottomMargin' => 'stretch'//pad
            ]);
        } catch (\Mpdf\MpdfException $e) {
            print "Creating an mPDF object failed with " . $e->getMessage();
        }
    }
}
