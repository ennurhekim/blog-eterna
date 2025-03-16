<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function users()
    {
        if (!auth()->user()->can('list users') && !auth()->user()->hasRole('admin'))
            return response_json(true, __('auth.permission_denied'), ["users" => $users]);

        $offset = 0;
        $limit = 10;
        $order = "Desc";
        $colums = ["email", "name", "surname", "phone"];
        $users = User::getAllUsers($colums, $offset, $limit,  $order);
        return response_json(true, __('validation.success_process'), ["users" => $users]);
    }
}
