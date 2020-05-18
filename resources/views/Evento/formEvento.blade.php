@extends('admin.dashboard')



<?php
if (isset($eventos)) {
    $action = route('editar_evento');
    $formulario_title = 'Editar Evento';
} else {
    $action = route('create_evento');
    $formulario_title = 'Criar Evento';
}
?>
@section('container-dashboard')
<div id="services-evento">
    <div class="form-box text-left col-md-6 m-auto">
        <div class="card-body row p-0">
            <div class="col-md-12 p-5">
                <h1 class="text-center">{{$formulario_title}}</h1>
                <hr>
                <div class="col-12 m-auto">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="post" id="form_conteudo_programatico" action="{{ $action }}" enctype="multipart/form-data">

                        @csrf

                        <input type="hidden" name="idEvento" value="{{ isset($eventos) ? $eventos->idEvento : '' }}">

                        <div class="form-group">
                            <label for="Nome">Nome Evento: </label>
                            <input type="Text" class="form-control" id="Name" name="Nome" value="{{ isset($eventos) ? $eventos->Nome : '' }}" placeholder="Insira um nome para o evento">
                        </div>

                        <div class="form-group">
                            <label for="Apelido">Apelido do Evento: </label>
                            <input type="Text" class="form-control" id="Apelido" name="Apelido" value="{{ isset($eventos) ?  $eventos->Apelido: '' }}" placeholder="Insira um apelido curto e sem espaços">
                        </div>

                        <div class="form-group ">
                            <label class="label" for="Responsavel">Responsável: </label>
                            <input type="Text" class="form-control " id="Responsavel" name="Responsavel" placeholder="Insira o nome do responsável do evento" value="{{ isset($eventos) ? $eventos->Responsavel : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="CargaHoraria">Carga Horária: </label>
                            <input type="number" class="form-control" id="CargaHoraria" name="CargaHoraria" placeholder="Insira o tempo de duração do evento" value="{{ isset($eventos) ? str_replace('H', "", $eventos->CargaHoraria) : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="Local">Local: </label>
                            <input type="Text" class="form-control" id="Local" name="Local" placeholder="Insira o local onde ocorrerá o evento" value="{{ isset($eventos) ? $eventos->Local : "" }}">
                        </div>

                        <div class="form-group input_fields_wrap">
                            <label for="ConteudoProgramatico">Conteúdo Programático: </label>

                            {{-- conteúdo Programático --}}

                            <textarea class="form-control" name="ConteudoProgramatico" placeholder="Descreva sucintamente o que ocorrerá durante o evento" id="ConteudoProgramatico">{{ isset($eventos) ? $eventos->ConteudoProgramatico : "" }}</textarea>
                        </div>

                        {{-- <button type="button" class="btn add_field_button" id="addScnt">+ Item</button> --}}

                        <div class="row form-group">
                            <div class="col-6">
                                <label for="dtInicio">Data Inicio: </label>
                                <input type="text" data-mask="00/00/0000" class="form-control" id="dtInicio" name="DataInicio" placeholder="Insira a data de início do evento" value="{{ isset($eventos) ? date('d/m/Y', strtotime($eventos->DataInicio)) : "" }}">

                            </div>
                            <div class="col-6">
                                <label for="dtFim">Data Fim: </label>
                                <input type="text" data-mask="00/00/0000" class="form-control" id="dtFim" name="DataFim" placeholder="Insira a data de encerramento do evento" value="{{ isset($eventos) ? date('d/m/Y', strtotime($eventos->DataFim)) : "" }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dtFim">Data Limite de Inscrição: </label>
                            <input type="text" data-mask="00/00/0000" class="form-control" id="dtlimite" name="DataLimiteInscricao" placeholder="Insira a data limite para realização da inscrição " value="{{ isset($eventos) ? date('d/m/Y', strtotime($eventos->DataLimiteInscricao)) : "" }}">
                        </div>

                        <div class="row form-group">
                            <div class="col-6">
                                <label for="hrInicio">Horária Inicio: </label>
                                <input type="time" class="form-control" id="hrInicio" name="HorarioInicio" placeholder="Insira o horário de início do evento" value="{{ isset($eventos) ? $eventos->HorarioInicio : "" }}">
                            </div>
                            <div class="col-6">
                                <label for="hrFim">Horário Fim: </label>
                                <input type="time" class="form-control" id="hrFim" name="HorarioFim" placeholder="Insira o horário de encerramento do evento" value="{{ isset($eventos) ? $eventos->HorarioFim : "" }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label for="hrFim">Os participantes poderão ministrar atividades?</label>
                            </div>
                            <div class="m-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="CondicaoCadastroDeAtividade" id="inlineRadio1" value="{{ isset($eventos) ? $eventos->CondicaoCadastroDeAtividade : "Sim" }}">
                                    <label class="form-check-label" for="inlineRadio1">Sim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="CondicaoCadastroDeAtividade" id="inlineRadio2" value="{{ isset($eventos) ? $eventos->CondicaoCadastroDeAtividade : "Nao" }}">
                                    <label class="form-check-label" for="inlineRadio2">Não</label>
                                </div>
                            </div>
                        </div>

                </div>


                <div class="form-group welcome-left d-flex align-items-center justify-content-center align-self-center">
                    <label for="logo">
                        <p class="text-center"><img class="icon" src="{{ asset('images/upload-icon.svg') }}"></p>
                    </label>
                    <input type="file" class="form-control" id="logo" name="logo" value="{{ isset($eventos) ? $eventos->Logo : "" }}">

                </div>

                <input type="hidden" name="idTemplate" value="{{ isset($eventos) ? $eventos->idTemplate : '' }}">

                <div class="form-group">
                    <label for="Site">Endereço Online (OPCIONAL): </label>
                    <input type="string" class="form-control" id="Site" name="Site" placeholder="Insira o endereço caso o evento possua um site próprio" value="{{ isset($eventos) ? $eventos->Site : "" }}">
                </div>

                <button type="submit" class="btn btn-outline-primary btn-block">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>



{{-- <script>
        $(function() {
            var scntDiv = $('#form_conteudo_programatico');
            var i = $('#form_conteudo_programatico div').size() + 1;


            $('#addScnt').live('click', function() {
                    $("<div class='row' id='alternativa'><div class='input-field col s12'><input id='title' type='text' name='alternativa'><label for='title'>Alternativa:</label></div></div>").appendTo(scntDiv);
                    i++;
                    return false;
            });

            $('#remScnt').live('click', function() {
                    if( i > 2 ) {
                        $(this).parents('p').remove();
                        i--;
                    }
                    return false;
            });
    });

    </script> --}}
@endsection
