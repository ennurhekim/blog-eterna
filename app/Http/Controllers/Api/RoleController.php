<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function listRole(Request $request)
    {
        if (!auth()->user()->hasRole('admin'))
            return response_json(true, __('auth.permission_denied'));

        $rols = Role::get();
        return response_json(true, __("validation.success"), ['rols' => $rols]);
    }
}
