<?php

namespace App\Http\Controllers;

use App\Models\SidebarsModel;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function index(Request $request): Response
    {
        $sidebars = new SidebarsModel();
        $sidebarsData = $sidebars->generateSidebar();

        return Inertia::render('Sidebar/SidebarIndex', [
            'sidebars' => $sidebarsData,
        ]);
    }

    /***
     * Handle reordering of sidebar items
     */
    public function reorder(Request $request)
    {
        $item = $request->sidebars;
        dd($item);
    }
}
