<?php


namespace App\Common\Helpers\Controller;

use App\Common\Helpers\Query\OracleProcedure;
use Carbon\Carbon;
use PDO;

/**
 * Class ManageItemsProcedure
 */
final class ManageItemsProcedure
{
    public static function create(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_acquisition.ManageItems', [
            'pTitle' => ['value' => $validated['title']],
            'pAuthor' => ['value' => $validated['author']],
            'pISBN' => ['value' => $validated['isbn']],
            'pItemType' => ['value' => $validated['item_type']],
            'pBatchId' => ['value' => $validated['batch_id'], 'type' => PDO::PARAM_INT],
            'pPublisherId' => ['value' => $validated['publisher_id'], 'type' => PDO::PARAM_INT],
            'pPubYear' => ['value' => $validated['pub_year'], 'type' => PDO::PARAM_INT],
            'pPubCity' => ['value' => $validated['pub_city']],
            'pCount' => ['value' => $validated['count'], 'type' => PDO::PARAM_INT],
            'pCost' => ['value' => $validated['cost'], 'type' => PDO::PARAM_INT],
            'pCurrency' => ['value' => $validated['currency']],
            'pLocation' => ['value' => $validated['location']],
            'pCreateDate' => ['value' => Carbon::now()->toDateString()],
            'pProgCode' => ['value' => $validated['prog_code']],
            'pUserCID' => ['value' => $validated['user_cid']],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }

    public static function edit(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_acquisition.UpdateItem', [
            'pInventoryID' => ['value' => $validated['inv_id'], 'type' => PDO::PARAM_INT],
            'pCost' => ['value' => $validated['cost'], 'type' => PDO::PARAM_INT],
            'pCurrency' => ['value' => $validated['currency']],
            'pLocation' => ['value' => $validated['location']],
            'pUserCID' => ['value' => $validated['user_cid']],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }

    public static function delete(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_acquisition.delete_items', [
            'pInvId' => ['value' => $validated['inv_id'], 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }

    public static function recreate(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_acquisition.ReCreateItems', [
            'pBookId' => ['value' => $validated['book_id'] ?? null, 'type' => PDO::PARAM_INT],
            'pJournal_IssueId' => ['value' => $validated['j_issue_id'] ?? null, 'type' => PDO::PARAM_INT],
            'pDiscId' => ['value' => $validated['disc_id'] ?? null, 'type' => PDO::PARAM_INT],
            'pBatchId' => ['value' => $validated['batch_id'], 'type' => PDO::PARAM_INT],
            'pCount' => ['value' => $validated['count'], 'type' => PDO::PARAM_INT],
            'pCost' => ['value' => $validated['cost'], 'type' => PDO::PARAM_INT],
            'pCurrency' => ['value' => $validated['currency']],
            'pLocation' => ['value' => $validated['location']],
            'pCreateDate' => ['value' => $validated['create_date']],
            'pUserCID' => ['value' => $validated['user_cid']],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }
}
