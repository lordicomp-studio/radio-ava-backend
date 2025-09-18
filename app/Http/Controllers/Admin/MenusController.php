<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required | min:3',
            'title' => 'required | min:3'
        ]);

        Menu::create([
            'name' => $validated['name'],
            'title' => $validated['title'],
        ]);

        return response(null, 200);
    }

    public function update(Request $request, Menu $menu){
        $validated = $request->validate([
            'name' => 'required | min:3',
            'title' => 'required | min:3'
        ]);

        $menu->update([
            'name' => $validated['name'],
            'title' => $validated['title'],
        ]);

        return response(null, 200);
    }

    public function indexDataApi(){
        return Menu::get();
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response(null, 200);
    }

    public function updateMenuSubmenu(Request $request){
        $validated = $request->validate([
            'id' => 'required | integer',
            'submenus' => 'nullable',
        ]);

        Menu::where('id', $validated['id'])->update([
            'submenus' => $validated['submenus'],
        ]);

        return response(null, 200);
    }
}
