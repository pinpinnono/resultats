<?php

namespace App\Http\Controllers;
use App\Models\Compte;
use App\Models\Invest;
use App\Models\History;
use App\Models\Daily;
use DateTime;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Http;


use Illuminate\Http\Request;

class InvestController extends Controller
{
    public $myfxAPI = "https://www.myfxbook.com/api/";

    private function myFXConnect($idinvest){
        $compte = Compte::find($idinvest);
        //var_dump($this->myfxAPI.'login.json?email='.$compte->login.'&password='.$compte->pass);
        $connect = Http::acceptJson()->get($this->myfxAPI.'login.json?email='.$compte->login.'&password='.$compte->pass);
        $session = json_decode($connect->getBody(), true);
        return $session;

    }

    private function myFXgetAccounts($session){
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        //var_dump($this->myfxAPI.'get-my-accounts.json?session='.$session);
        $comptes = Http::acceptJson()->get($this->myfxAPI.'get-my-accounts.json?session='.$session);
        return $comptes;
    }
    /**
     * Show the comptes for a given user.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $compte = Compte::find($id);
        $comptesInvest = "";
        $user_id = auth()->user()->id;
        if(str_contains($compte->label, 'myfxbook')){
            $connect = $this->myFXConnect($id);
            $comptesInvest = $this->myFXgetAccounts($connect['session']);
        }
        if($comptesInvest != null){
            $data = json_decode($comptesInvest->getBody(), true);
            
            foreach($data['accounts'] as $account => $val){
                $invest = Invest::updateOrCreate(
                    ['account_id' => $val['id'],'type' => "MyFxBook",'user_id' => $user_id,'compte_id' => $compte->id],
                    ['account_id' => $val['id'],'type' => "MyFxBook",'data' => json_encode($val),'user_id' => $user_id,'compte_id' => $compte->id]
                );
            }
        }
        $invests = Invest::where("compte_id","=",$compte->id)->where('user_id', '=', $user_id)->get();

        return view('invest.invest', [
            'id_invest'=>$id,
            'invests' => $invests
        ]);
    }
   
    public function historyMyFx(Request $request, $id, $id_invest){
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $connect = $this->myFXConnect($id_invest);
        $daily = Http::acceptJson()->get($this->myfxAPI.'get-history.json?session='.$connect["session"].'&id='.$id);
        $deco = Http::acceptJson()->get($this->myfxAPI.'logout.json?session='.$connect["session"]);
        $data = json_decode($daily->getBody(), true);
        // echo "<pre>";
        // var_dump($data['history']);
        foreach($data['history'] as $invest){
            $dateOpen = new DateTime($invest['openTime']);
            $closeTime = new DateTime($invest['closeTime']);
            History::updateOrCreate(
                ['account_id' => $id,'opentime' => $dateOpen->format('Y-m-d H:i:s'),'closetime' => $closeTime->format('Y-m-d H:i:s'),'action' => $invest['action']],
                ['account_id' => $id,
                'opentime' => $dateOpen->format('Y-m-d H:i:s'),
                'closetime' => $closeTime->format('Y-m-d H:i:s'),
                'action' => $invest['action'],
                'commission' => $invest['commission'],
                'interest' => $invest['interest'],
                'profit' => $invest['profit'],
                'comment' => $invest['comment'],
                'balance' => "0.0"]
            );
        }
        return view('invest.investhistory', [
            'id' => $id_invest,
            'id_invest'=>$id,
            'invests' => $data
        ]);
    }

    public function dailyMyFx(Request $request, $id, $id_invest){
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        $connect = $this->myFXConnect($id_invest);
        $dateNow = new DateTime('now');
        //$url= "https://www.myfxbook.com/api/get-data-daily.json?session=eHdCaUo1tlIFwJggtsx4493300&id=9621441&start=2022-06-01&end=2022-06-30";
        $url = $this->myfxAPI.'get-data-daily.json?session='.$connect["session"].'&id='.$id.'&start=2022-01-01&end='.$dateNow->format('Y-m-d');
        $daily = Http::acceptJson()->get($url);
        $deco = Http::acceptJson()->get($this->myfxAPI.'logout.json?session='.$connect["session"]);
        $data = json_decode($daily->getBody(), true);
        if($data==null){
            $day = Daily::select("account_id", "date", "balance", "floatingPL","profit","growthEquity")
            ->where('account_id', '=', $id)
            ->orderBy('date','desc')
            ->get();
            $day = $day->toArray();
            if($day == null){
                return redirect()->back()->with('error', 'Api injoignable. Et rien en BDD'.$connect["session"]);
            }
            return view('invest.investdaily', [
                'id' => $id_invest,
                'id_invest'=>$id,
                'days' => $day
            ])->with('error', 'Api injoignable.');
        }
        foreach($data['dataDaily'] as $invest){
            // var_dump($invest);
            // die();
            $date = new DateTime($invest[0]['date']);
            Daily::updateOrCreate(
                ['account_id' => $id,'date' => $date->format('Y-m-d H:i:s')],
                ['account_id' => $id,
                'date' => $date->format('Y-m-d H:i:s'),
                'balance' => $invest[0]['balance'],
                'pips' => $invest[0]['pips'],
                'lots' => $invest[0]['lots'],
                'floatingPL' => $invest[0]['floatingPL'],
                'profit' => $invest[0]['profit'],
                'growthEquity' => $invest[0]['growthEquity'],
                'floatingPips' => $invest[0]['floatingPips']
                ]
            );
        }
        $day = Daily::select("account_id", "date", "balance", "floatingPL","profit","growthEquity")
        ->where("account_id",'=',$id)
        ->orderBy('date')
        ->get();

        return view('invest.investdaily', [
            'id' => $id_invest,
            'id_invest'=>$id,
            'days' => $day
        ]);
    }

    public function export(Request $request, $id_invest, $id){
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        //  Le nom du fichier avec l'extension : .xlsx ou .csv
        $file_name = "export".$id_invest.".xlsx";

        // 3. On récupère données de la table "clients"
        $date = new DateTime('now'); $date->modify('-90 day'); 
        //die(var_dump($date->format('Y-m-d H:i:s')));
        //$date = $date->format('Y-m-d h:i:s');
        // or you can use '-90 day' for deduct $date = $date->format('Y-m-d h:i:s');
        $clients = History::select("account_id", DB::raw("DATE_FORMAT(opentime, '%d/%m/%Y %H:%i:%s') as opentime"), DB::raw("DATE_FORMAT(closetime, '%d/%m/%Y %H:%i:%s') as closetime"), "action","commission","interest","profit","comment","balance")
        ->where("account_id",'=',$id)
        ->whereDate('closetime', '>=', $date->format('Y-m-d'))
        ->orderBy('closetime')
        ->get();

        // 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
        $writer = SimpleExcelWriter::streamDownload($file_name);

            // 5. On insère toutes les lignes au fichier Excel $file_name
        $writer->addRows($clients->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
        $this->historyMyFx($id, $id_invest);
    
    }

    public function exportDaily(Request $request, $id_invest, $id){
        if(!isset(auth()->user()->id))
            return redirect()->route('voyager.login');
        //  Le nom du fichier avec l'extension : .xlsx ou .csv
        $file_name = "exportDaily".$id_invest.".xlsx";

        // 3. On récupère données de la table "clients"
        $date = new DateTime('now'); $date->modify('-90 day'); 
        //die(var_dump($date->format('Y-m-d H:i:s')));
        //$date = $date->format('Y-m-d h:i:s');
        // or you can use '-90 day' for deduct $date = $date->format('Y-m-d h:i:s');
        $clients = Daily::select("account_id", DB::raw("DATE_FORMAT(date, '%d/%m/%Y') as date"), "balance", "floatingPL","profit","growthEquity")
        ->where("account_id",'=',$id)
        ->orderBy('date')
        ->get();
        // 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
        $writer = SimpleExcelWriter::streamDownload($file_name);

            // 5. On insère toutes les lignes au fichier Excel $file_name
        $writer->addRows($clients->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
        $this->historyMyFx($id, $id_invest);
    
    }
}
