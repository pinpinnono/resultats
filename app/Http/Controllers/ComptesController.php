<?php

namespace App\Http\Controllers;
use App\Models\Compte;


use Illuminate\Http\Request;

class ComptesController extends Controller
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
        return view('comptes', [
            'user' => $user_id,
            'comptes' => $comptes
        ]);
    }
    /**
     * Show the comptes for a given user.
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $compte = Compte::find($id);
        return view('comptesEdit', [
            'comptes' => $compte,
            'options' => $this->options
        ]);
    }

    /**
     * Show the comptes for a given user.
     *
     * @param $id
     * 
     */
    public function update(Request $request)
    {
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        Compte::where('id', $request->input('id'))
                ->update(['designation' => $request->input('designation'),
                         'label'=>$request->input('label'),
                         'login'=>$request->input('login'),
                         'pass'=>$request->input('pass')]
                        );
        return redirect()->route('comptes')->with('success', 'Le compte est a jour.'); 
    }

    public function delete($id)
    {
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $compte = Compte::find($id)->delete();
        return redirect()->route('comptes')->with('alert', 'Le compte est supprimé.'); 
    }

    public function add(){
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $user_id = auth()->user()->id;
        return view('comptesAdd', [
            'user' => $user_id,
            'options' => $this->options
        ]);
    }
    public function store(Request $request){
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $user_id = auth()->user()->id;

        $compte = new Compte();
        $compte->user_id = $user_id;
        $compte->designation = $request->input('designation');
        $compte->label = $request->input('label');
        $compte->login = $request->input('login');
        $compte->pass = $request->input('pass');
        $compte->save();

        return redirect()->route('comptes')->with('success', 'Le compte est créé.'); 
    }


}
