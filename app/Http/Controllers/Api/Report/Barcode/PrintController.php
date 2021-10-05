<?php

namespace App\Http\Controllers\Api\Report\Barcode;

use App\Common\Helpers\Query\OracleProcedure;
use App\Exceptions\ReturnResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\Barcode\BarcodePrintRequest;
use App\Models\Acquisition\Item\Item;
use PDO;
use TCPDF;

class PrintController extends Controller
{
    public function __invoke(BarcodePrintRequest $request)
    {
        $validated = $request->validated();

        $barcodes = Item::barcodeQuery()->whereIn('i.inv_id', $validated['inventories'])->get()->toArray();

        $pdf = new TCPDF('landscape', PDF_UNIT,
            [81, 49], true, 'UTF-8', false);
        $pdf->setTitle('Barcodes');
        $pdf->setMargins(5, 5, 5);
        $pdf->SetAutoPageBreak(false);

        $style = [
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false,
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 12,
            'stretchtext' => 4,
        ];

        foreach ($barcodes as $barcode) {
            $title = $barcode['title'];
            $invId = $barcode['id'];
            $barcodeNo = $barcode['barcode'];
            $author = $barcode['author'];

            if (!$this->setTagPrinted($invId)) {
                throw new ReturnResponseException('Procedure error', 500);
            }

            if (strlen($title) > 30) {
                $title = mb_substr($title, 0, 30) . '...';
            }

            if (strlen($author) > 30) {
                $author = mb_substr($author, 0, 30) . '...';
            }

            $pdf->addPage();
            $pdf->SetFont('freeserif','B',8);
            $pdf->SetFontSize(8);
            $pdf->Cell(0, 0, $author);
            $pdf->Ln();
            $pdf->SetFontSize(10);
            $pdf->Cell(0, 0, $title);
            $pdf->SetFontSize(12);
            $pdf->write1DBarcode($barcodeNo, 'EAN8', 15, 15, 50, 20, 1, $style, 'N');
            $pdf->Cell(0, 1, $invId);
            $pdf->endPage();
        }

        $pdf->Output(now() . '.pdf', 'D');
    }

    private function setTagPrinted(int $id): bool
    {
        $procedure = new OracleProcedure('pkg_acquisition.set_tags_printed', [
            'pInvId' => ['value' => $id, 'type' => PDO::PARAM_INT],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);

        $procedure->call();
        return ((int) $procedure->getOutputParams()['pRes']['value']) === 1;
    }
}
