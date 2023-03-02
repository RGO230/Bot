<?php

namespace App\Http\Controllers;

use App\Models\Tguser;
use Illuminate\Http\Request;

class TguserController extends Controller
{
    public function index()
    {
        $tguser = Tguser::orderBy('id', 'desc')
            ->paginate(10); 
        return view('tguser.index')->with('tguser', $tguser);
    }
    public function edit(Tguser $tguser)
    {
        return view('tguser.edit', [
            'tguser' => $tguser
        ]);
    }
    public function update(Request $request, Tguser $tguser)
    {
        $request->validate([
            'isvip'=>'int'
        ]);

        $tguser->update([
            "isvip"=>$request->isvip
        ]);

        return redirect()->route('tguser.index');
    }

}
