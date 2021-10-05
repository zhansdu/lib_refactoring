<?php


namespace App\Common\Helpers\Controller;


use App\Common\Helpers\Query\OracleProcedure;
use PDO;

class EditMaterialProcedure
{
    public static function edit(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_cataloging.insert_update_materials', [
            'pCallNumber' => ['value' => $validated['call_number']],
            'pIsbn' => ['value' => $validated['isbn']],
            'pISSN' => ['value' => $validated['issn']],
            'pVolume' => ['value' => $validated['volume']],
            'pTitle' => ['value' => $validated['title']],
            'pMainAuthor' => ['value' => $validated['main_author']],
            'pOtherAuthors' => ['value' => $validated['other_author']],
            'pPublisher' => ['value' => $validated['publisher']],
            'pPub_year' => ['value' => $validated['year']],
            'pPub_city' => ['value' => $validated['city']],
            'pPageNumber' => ['value' => $validated['page_number']],
            'pParallel_title' => ['value' => $validated['parallel_title']],
            'pTitle_related_info' => ['value' => $validated['title_related_info']],
            'pLanguage' => ['value' => $validated['language']],
            'pType' => ['value' => $validated['type']],
            'pMaterialID' => ['value' => $validated['id']],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }

    /**
     * @param array $validated
     * @return array
     */
    public static function loadXml(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_cataloging.InsertUpdateBibliographicInfo', [
            'pBookId' => ['value' => $validated['id'], 'type' => PDO::PARAM_INT],
            'pJournalId' => ['value' => $validated['id'], 'type' => PDO::PARAM_INT],
            'pDiscId' => ['value' => $validated['id'], 'type' => PDO::PARAM_INT],
            'pBibliographicInfo' => ['value' => $validated['xml'], 'type' => OCI_B_CLOB],
            'pAppLog' => ['value' => 0, 'type' => PDO::PARAM_INT],
            'pUSERCID' => ['value' => $validated['emp_id'], 'type' => PDO::PARAM_INT],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }
}
