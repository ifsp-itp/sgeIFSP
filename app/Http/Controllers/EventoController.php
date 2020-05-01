<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\EventoModel;
use App\AtividadeModel;
use App\ImagesEvento;
use App\userEventoModel;
use App\UserAtividadeModel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EventRequest;
use Gate;

class EventoController extends Controller
{

    public function show($Apelido){

        //pegando evento pelo apelido
        $eventos = EventoModel::where('Apelido', '=', $Apelido)->get()->first();
        $atividades = AtividadeModel::where('idEvento','=',$eventos->idEvento)->orderBy('DataInicio')->get();
        $images = ImagesEvento::where('idEvento', $eventos["idEvento"])->get();
        $url = $eventos['Site'];
        if($url <> "vazio"){  
            return redirect($url);
        }else{
            if (auth()->user()){
                $atividades = DB::table('atividade')->where('idEvento', $eventos->idEvento)->get();
                foreach ($atividades as $atividade) {
                    $atividade_inscrito = UserAtividadeModel::where('idUser', '=', auth()->user()->id)->where('idAtividade', '=', $atividade->idAtividade)->count();
                    if($atividade_inscrito !=0){
                        $atividade->inscrito = true;
                    }else{
                        $atividade->inscrito = false;
                    }
                }

                $evento_inscrito = userEventoModel::where('idUser', '=', auth()->user()->id)->where('idEvento', '=', $eventos->idEvento)->count();
                if($evento_inscrito !=0){
                    $eventos->inscrito = true;
                }else{
                    $eventos->inscrito = false;
                }

                return view("Evento.show", compact('eventos','atividades','images'));
            }


            return view("Evento.show", compact(['eventos','atividades','images']));
        }
    }

    public function ShowForm(Request $data) {
        if(isset($data->idEvento)){
            $eventos = DB::table('evento')->where('idEvento', $data->idEvento)->first();
            return view('Evento.formEvento',compact('eventos'));
        }else{
            return view('Evento.formEvento');
        }


    }

    public function create(EventRequest $data){
        if(empty($data['Site'])){
            $data['Site'] = "vazio";
        }
        $validated = $data->validated();
        //dd($data);
        if( (strtotime($data['DataInicio']) <= strtotime($data['DataFim']))
        && (strtotime($data['DataLimiteInscricao']) <= strtotime($data['DataFim']) ) ){
            if ($data->hasFile('logo') && $data->file('logo')->isValid()) {
                $name = uniqid(date('HisYmd'));
                $extension = $data->logo->extension();
                $namefile = "{$name}.{$extension}";
                $upload = $data->logo->store('logo_evento');
                //$visibility = Storage::getVisibility($upload);
                //Storage::setVisibility($upload,'public');

                EventoModel::create([
                    'CondicaoEvento' => 'Ativado',
                    'CondicaoCadastroDeAtividade'=> $data['CondicaoCadastroDeAtividade'],
                    'Nome' => $data['Nome'],
                    'Apelido' => $data['Apelido'],
                    'DataInicio'   => $data['DataInicio'],
                    'DataFim'   => $data['DataFim'],
                    'DataLimiteInscricao'   => $data['DataLimiteInscricao'],
                    'ConteudoProgramatico'   => $data['ConteudoProgramatico'],
                    'Responsavel' => $data['Responsavel'],
                    'CargaHoraria'   => $data['CargaHoraria']."H",
                    'HorarioInicio'   => $data['HorarioInicio'],
                    'HorarioFim'   => $data['HorarioFim'],
                    'Local'   => $data['Local'],
                    'Logo'   => $upload,
                    'Site'   => $data['Site'],
                    ]);
                return redirect()->route('list_evento_admin');
            }
        }else{
            return redirect()->route('showForm_create_evento')->withErrors('Verifique as datas inseridas! As datas de inicio e de limite de inscrição devem ser anteriores a data de término.');
        }
    }

    public function read() {
        $eventos = DB::table('evento')->get();

        if (auth()->user()){
            $eventos = DB::table('evento')->get();
            foreach ($eventos as $evento) {
                $evento_inscrito = userEventoModel::where('idUser', '=', auth()->user()->id)->where('idEvento', '=', $evento->idEvento)->count();
                if($evento_inscrito !=0){
                    $evento->inscrito = true;
                }else{
                    $evento->inscrito = false;
                }
            }

            return view('Evento.list',compact('eventos'));
        }
        return view('Evento.list',compact('eventos'));

    }

    public function read_dashboard() {
        $eventos = EventoModel::orderBy('idEvento')->get();

        return view('admin.listEvento',compact('eventos'));

    }

    public function update(Request $data)
    {
        if ($data->hasFile('logo') && $data->file('logo')->isValid()) {
            $name = uniqid(date('HisYmd'));
            $extension = $data->logo->extension();
            $namefile = "{$name}.{$extension}";
            $upload = $data->logo->store('logo_evento');

            $eventos = EventoModel::findOrFail($data['idEvento']);
            $eventos->CondicaoCadastroDeAtividade = $data['CondicaoCadastroDeAtividade'];
            $eventos->Nome = $data['Nome'];
            $eventos->Apelido = $data['Apelido'];
            $eventos->DataInicio = $data['DataInicio'];
            $eventos->DataFim = $data['DataFim'];
            $eventos->DataLimiteInscricao = $data['DataLimiteInscricao'];
            $eventos->ConteudoProgramatico = $data['ConteudoProgramatico'];
            $eventos->Responsavel = $data['Responsavel'];
            $eventos->CargaHoraria = $data['CargaHoraria'].'H';
            $eventos->HorarioInicio = $data['HorarioInicio'];
            $eventos->HorarioFim = $data['HorarioFim'];
            $eventos->Local = $data['Local'];
            $eventos->Logo = $upload;
            $eventos->save();

            return redirect()->route('list_evento_admin');
        }
    }

    public function delete (Request $data) {

        $eventos = EventoModel::findOrFail($data['idEvento']);
            $eventos->CondicaoEvento = 'Desativado';
            $eventos->save();

        return redirect()->route('list_evento_admin');
    }
}


