<?php

namespace App\Http\Controllers;
use App\Models\Compte;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $options = ["https://www.myfxbook.com/api/"=> "MyFxBook","https://www.zob"=> "MyZobBook"];
    /**
     * Show the comptes for a given user.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $user_id = auth()->user()->id;
        $comptes = Compte::where('user_id','=',$user_id)->get();
        return view('dashboard.comptes', [
            'user' => $user_id,
            'comptes' => $comptes
        ]);
    }
   

}
