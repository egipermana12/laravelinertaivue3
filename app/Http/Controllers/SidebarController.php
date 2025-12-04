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
        $items = $request->sidebars;
        if (!is_array($items)) {
            return response()->json(['message' => 'Invalid data format'], 422);
        }

        // Call the recursive function to update the order
        $this->updateSidebarOrder($items);
        return back()->with('success', 'Sidebar berhasil diupdate!');
    }

    /***
     * handling recusive function to update sidebar order
     */
    private function updateSidebarOrder(array $items, int $parentId = 0)
    {
        foreach( $items as $sort => $item)
        {
            SidebarsModel::where('id', $item['id'])->update([
                'parentId' => $parentId,
                'sortOrder' => $sort,
            ]);

            if (isset($item['items']) && is_array($item['items'])) {
                $this->updateSidebarOrder($item['items'], $item['id']);
            }
        }
    }

    /*** 
     * handling update of sidebar item
    */
    public function update(Request $request, $id)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|string|max:255',
            'icon'  => 'required|string|max:100',
        ]);


        SidebarsModel::where('id', $id)->update($validated);

        return back()->with('success', 'Sidebar updated!');   
    }

    /*** 
     * handling creation of new sidebar item
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|string|max:255',
            'icon'  => 'required|string|max:100',
        ]);

        SidebarsModel::create($validated);

        return back()->with('success', 'New sidebar item created!');
    }
}
