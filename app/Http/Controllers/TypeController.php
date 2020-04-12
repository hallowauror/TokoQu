<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;

class TypeController extends Controller
{
    public function index()
    {
        $type = Type::orderBy('created_at', 'DESC')->paginate(10);
        return view('type.index', compact('type'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type_name' => 'required|string|max:100'
        ]);

        $type = Type::firstOrCreate(['type_name' => $request->type_name]);
        return redirect()->back()->with(['success' => 'Type : <strong>' . $type->type_name . '</strong> Ditambahkan']);
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();
        return redirect()->back()->with(['success' => 'Type : <strong>' . $type->type_name . '</strong> Dihapus']);
    }

}
