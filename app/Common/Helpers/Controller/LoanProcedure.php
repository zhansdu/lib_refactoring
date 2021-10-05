<?php


namespace App\Common\Helpers\Controller;


use App\Common\Helpers\Query\OracleProcedure;
use PDO;

class LoanProcedure
{
    public static function backMaterial(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_library.loan_actions', [
            'pType' => ['value' => 1, 'type' => PDO::PARAM_INT],
            'pLoanID' => ['value' => $validated['loan_id'], 'type' => PDO::PARAM_INT],
            'pUserId' => ['value' => $validated['user_cid']],
            'pWebLogId' => ['value' => $validated['web_log_id'], 'type' => PDO::PARAM_INT],
            'pInvId' => ['value' => $validated['inv_id'], 'type' => PDO::PARAM_INT],
            'pDueDate' => ['value' => $validated['due_date']],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }

    public static function giveMaterial(array $validated): array
    {
        $procedure = new OracleProcedure('pkg_library.loan_actions', [
            'pType' => ['value' => 2],
            'pLoanID' => ['value' => 0, 'type' => PDO::PARAM_INT],
            'pUserId' => ['value' => $validated['user_cid']],
            'pWebLogId' => ['value' => $validated['web_log_id'], 'type' => PDO::PARAM_INT],
            'pInvId' => ['value' => $validated['inv_id'], 'type' => PDO::PARAM_INT],
            'pDueDate' => ['value' => $validated['due_date']],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        return $procedure->getOutputParams();
    }
}
