<?php


namespace App\Models\Acquisition\Item;


use App\Common\Helpers\Query\OracleProcedure;
use Carbon\Carbon;
use PDO;

trait ItemManage
{
    public static function create(array $attributes): object
    {
        $procedure = new OracleProcedure('pkg_acquisition.manage_items', [
            'pTitle' => ['value' => $attributes['title']],
            'pAuthor' => ['value' => $attributes['author']],
            'pISBN' => ['value' => $attributes['isbn']],
            'pItemType' => ['value' => $attributes['item_type'], 'type' => PDO::PARAM_STR_CHAR],
            'pBatchId' => ['value' => $attributes['batch_id'], 'type' => PDO::PARAM_INT],
            'pPublisherId' => ['value' => $attributes['publisher_id'], 'type' => PDO::PARAM_INT],
            'pPubYear' => ['value' => $attributes['pub_year'], 'type' => PDO::PARAM_INT],
            'pPubCity' => ['value' => $attributes['pub_city']],
            'pCount' => ['value' => $attributes['count'], 'type' => PDO::PARAM_INT],
            'pCost' => ['value' => $attributes['cost'], 'type' => PDO::PARAM_INT],
            'pCurrency' => ['value' => $attributes['currency'], 'type' => PDO::PARAM_STR_CHAR],
            'pLocation' => ['value' => $attributes['location'], 'type' => PDO::PARAM_STR_CHAR],
            'pCreateDate' => ['value' => Carbon::now()->toDateString()],
            'pProgCode' => ['value' => $attributes['prog_code']],
            'pUserCID' => ['value' => $attributes['user_cid'], 'type' => PDO::PARAM_STR_CHAR],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        $result = $procedure->getOutputParams();

        return (new class($result['pRes']['value']) {
            private $key;

            public function __construct(int $key)
            {
                $this->key = $key;
            }

            public function getKey(): int
            {
                return $this->key;
            }
        });
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        $procedure = new OracleProcedure('pkg_acquisition.UpdateItem', [
            'pInventoryID' => ['value' => $this->inv_id, 'type' => PDO::PARAM_INT],
            'pCost' => ['value' => $attributes['cost'], 'type' => PDO::PARAM_INT],
            'pCurrency' => ['value' => $attributes['currency'], 'type' => PDO::PARAM_STR_CHAR],
            'pLocation' => ['value' => $attributes['location'], 'type' => PDO::PARAM_STR_CHAR],
            'pUserCID' => ['value' => $attributes['user_cid'], 'type' => PDO::PARAM_STR_CHAR],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        $result = $procedure->getOutputParams();

        return $result['pRes']['value'] === 1;
    }

    public function delete(): ?bool
    {
        $procedure = new OracleProcedure('pkg_acquisition.delete_items', [
            'pInvId' => ['value' => $this->inv_id, 'isOut' => true, 'type' => PDO::PARAM_INT]
        ]);
        $procedure->call();
        $result = $procedure->getOutputParams();

        return $result['pInvId']['value'] === 1;
    }
}
