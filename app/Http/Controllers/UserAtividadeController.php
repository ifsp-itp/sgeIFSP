<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\userEventoModel;
use App\UserAtividadeModel;

class UserAtividadeController extends Controller
{
    public function Inscrever(Request $data){
        //Verificando se o usuário está inscrito no evento que possui essa tividade
    	$evento_inscrito = DB::table('user_evento')
    	->where('idEvento','=',$data['idEvento'])
    	->where('idUser','=',auth()->user()->id)
    	->count();

        //dd($evento_inscrito);
    	if($evento_inscrito != 0){
            $atividade_inscrito = DB::table('user_atividade')
            ->where('idAtividade','=',$data['idAtividade'])
            ->where('idUser','=', auth()->user()->id)
            ->count();
            
            if($atividade_inscrito == 0){
               UserAtividadeModel::create([
                    'idAtividade' => $data['idAtividade'],
                    'idUser' => auth()->user()->id
                ]);
            }else{
                return redirect()->back()->with('error', 'Já está inscrito nessa atividade');
            }

    	}else{
    		return redirect()->back()->with('error', 'É necessario estar inscrito no evento dessa atividade');
    	}

    	return redirect()->back()->with('success', 'Sucesso, você está inscrito no evento!');

    }
    public function desinscrever(Request $data)
	{
		UserAtividadeModel::where('idAtividade','=',$data['idAtividade'])->where('idUser','=',auth()->user()->id)->delete();
		
		return redirect()->back();
	}
}
