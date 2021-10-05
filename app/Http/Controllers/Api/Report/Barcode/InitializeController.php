<?php


namespace App\Http\Controllers\Api\Report\Barcode;


use App\Common\Helpers\Query\OracleProcedure;
use App\Exceptions\ReturnResponseException;
use Illuminate\Http\JsonResponse;
use PDO;

class InitializeController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->setTagInitialized($id)) {
            throw new ReturnResponseException('Procedure error', 100);
        }

        return response()->json([
            'res' =>  'Initialized'
        ]);
    }

    private function setTagInitialized(int $id): bool
    {
        $procedure = new OracleProcedure('pkg_acquisition.set_tags_initialized', [
            'pInvId' => ['value' => $id, 'type' => PDO::PARAM_INT],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);

        $procedure->call();
        return (int) $procedure->getOutputParams()['pRes'] === 1;
    }
}
