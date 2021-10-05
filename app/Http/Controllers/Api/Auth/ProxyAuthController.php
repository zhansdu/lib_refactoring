<?php

namespace App\Http\Controllers\Api\Auth;

use App\Common\Helpers\Controller\AuthProcedure;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProxyAuthController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required',
            'pass' => 'required'
        ]);
        $validated['username'] = $validated['user'];
        $validated['password'] = $validated['pass'];

        unset($validated['user']);
        unset($validated['pass']);

        $server = [
            'REMOTE_ADDR' => $request->server('REMOTE_ADDR'),
            'HTTP_USER_AGENT' => $request->server('HTTP_USER_AGENT')
        ];

        AuthProcedure::auth($validated, $server);

        return response('+OK');
    }
}
