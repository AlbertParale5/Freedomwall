<?php

namespace App\Http\Controllers;

use App\Fwall;
use Illuminate\Http\Request;

class FwallController extends Controller
{
    
    public function load_data()
    {
        $Fwall = Fwall::latest()->get();
        return compact('Fwall');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',       
            'body' => 'required'
        ]);
        $Fwall = new Fwall;

        $Fwall->username = $request->username;
        $Fwall->body = $request->body;
        $Fwall->save();
        return response()->json();
    }

    public function edit($id)
    {
        $Fwall = Fwall::find($id);
        if ($Fwall) 
        {
            return response()->json([
                'status' => 200,
                'Fwall' => $Fwall,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'Fwall' => 'Student not Found',
            ]);
        }
    }


    public function update($id) 
    {
        $Fwall = Fwall::find($id);
        $Fwall->username = request('username');
        $Fwall->body = request('body');
        $Fwall->save();
        return response()->json();

    }

    public function destroy($id)
    {
        $Fwall = Fwall::findOrFail($id);
        $Fwall->delete();
    }
    
    
}
