<?php


namespace App\Http\Controllers\Api\Cataloging;

use App\Http\Controllers\Controller;
use App\Models\Media\MaterialTypeFactory;
use Illuminate\Support\Facades\DB;
use TCPDF;

class PrintController extends Controller
{
    public function __invoke(string $type, int $id)
    {
        $keyName = explode('.', MaterialTypeFactory::getMaterialClass($type)->getKeyName());
        $marcData = DB::table('view_marc_data')->select()->where($keyName[1] ?? $keyName[0], $id)->orderBy('id')->get()->toArray();

        $data = array_merge(
            self::getFieldData($marcData, '010.c'),
            self::getFieldData($marcData, '010.n'),
            self::getFieldData($marcData, '010.s'),
            self::getFieldData($marcData, '260.c'),
            self::getFieldData($marcData, '490.v'),
        );

        $pdf = new TCPDF('portrait', PDF_UNIT,
            [25, 47], true, 'UTF-8', false);

        $pdf->setTitle('Call Number');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setMargins(5, 5, 5);
        $pdf->SetAutoPageBreak(false);

        $pdf->addPage();
        $pdf->SetFont('freeserif', 'B', 10);
        $pdf->SetFontSize(14);

        foreach ($data as $field) {
            $pdf->Cell(0, 0, $field, 0, 0, 'C');
            $pdf->Ln();
        }

        $pdf->endPage();

        $pdf->Output(now() . '.pdf', 'D');
    }

    /**
     * @param array $fields
     * @param string $key
     * @return array
     */
    private static function getFieldData(array $fields, string $key): array
    {
        $data = [];
        $indexes = array_keys(array_column($fields, 'id'), $key);

        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                $data[] = $fields[$index]->data ?? '';
            }
        }

        return $data;
    }
}
