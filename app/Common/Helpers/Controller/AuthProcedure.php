<?php


namespace App\Common\Helpers\Controller;


use App\Common\Helpers\Query\OracleProcedure;
use App\Models\User\Employee;
use App\Models\User\Student;
use Illuminate\Validation\ValidationException;
use PDO;

final class AuthProcedure
{
    public static function auth(array $validated, array $serverVars)
    {
        $procedure = new OracleProcedure('AuthenticateUser', [
            'pUname' => ['value' => $validated['username']],
            'pPSW' => ['value' => $validated['password']],
            'pUserIP' => ['value' => $serverVars['REMOTE_ADDR']],
            'pDeviceInfo' => ['value' => $serverVars['HTTP_USER_AGENT']],
            'pIsVerified' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
            'pRes' => ['value' => 0, 'isOut' => true, 'type' => PDO::PARAM_INT],
        ]);
        $procedure->call();
        $result = $procedure->getOutputParams();

        $user = Student::find($validated['username']);

        if (empty($user)) {
            $user = Employee::where("hname", $validated['username'])->first();
        }

        if ($result['pIsVerified']['value'] != 1 || empty($user)) {
            throw ValidationException::withMessages([
                'user' => ['The provided credentials are incorrect']
            ]);
        }

        return $user;
    }
}
