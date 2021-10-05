<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user('api-student') ?? $request->user('api-employee');
        if ($user) {
            $user->tokens->each(function($token, $key) {
                $token->delete();
            });
        }
        Session::forget('user');
        return response()->json(['res' => [
            'message' => 'Logged out'
        ]]);
    }
}
