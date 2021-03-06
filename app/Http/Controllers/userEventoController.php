<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\userEventoModel;
use App\EventoModel;

class userEventoController extends Controller
{
    public function inscrever(Request $data){
    	$evento_inscrito = DB::table('user_evento')
    	->where('idEvento','=',$data['idEvento'])
    	->where('idUser','=',auth()->user()->id)
    	->count();


    	if($evento_inscrito == 0){
	        userEventoModel::create([
	            'idEvento' => $data['idEvento'],
				'idUser' => auth()->user()->id
			]);
			return redirect()->back();
        }else{
			return redirect()->route("listEvent");
		}

	}


	public function listaDeChamada(Request $data){
		$participantes = DB::table('user_evento')
							->join('users','users.id','=','user_evento.idUser')
							->where('idEvento', '=',$data['idEvento'])
							->get();


        $evento = DB::table('evento')->select('idEvento','Nome')->where('idEvento',$data->idEvento)->get();

		return view('admin.listaDeChamadaEvento',compact(['participantes','evento']));
    }



    public function update(Request $data){
        $idUserEvento   = $data['idUserEvento'];
		$status         = $data['status'];

		if($status == 'P'){
			userEventoModel::where('idUserEvento',$idUserEvento)->update(['presente' => True, 'ausente' => False]);
		}elseif($status == 'A'){
			userEventoModel::where('idUserEvento',$idUserEvento)->update(['presente' => False, 'ausente' => True]);
		}

        return redirect()->back();
	}

	public function desinscrever(Request $data)
	{
		userEventoModel::where('idEvento','=',$data['idEvento'])->where('idUser','=',auth()->user()->id)->delete();

		return redirect()->back();
	}
}
