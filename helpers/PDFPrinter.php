<?php

    require_once('third-party/dompdf/autoload.inc.php');
    use Dompdf\Dompdf;

    class PDFPrinter{

        public function __construct()
        {

        }

        public function render($textoHTML, $nombreArchivo, $opcion)
        {
            $dompdf = new Dompdf();

            $dompdf->loadHtml($textoHTML);

            $dompdf->setPaper('A4', 'portrait');
            
            $dompdf->render();

            $dompdf->stream($nombreArchivo, ['Attachment' => $opcion]);

        }

    }

?>
