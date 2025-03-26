<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function show()
    {
       
        $logs = Activity::all();
        response_json(true, "Başarılı", $logs);
    }
}
