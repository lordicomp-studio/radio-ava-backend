<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Repositories\Admin\DashboardRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function panel(DashboardRepository $repository){
        return Inertia::render('Admin/Dashboard');
    }

}
