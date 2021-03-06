@extends('template.main')

@section('color-bg')
background-image-solid
@endsection


@section('navbar')
@include('components.navbar')
@endsection

@section('footer')
@include('components.footer')
@endsection

@section('content')

<script>
    var event_begin = document.querySelector('input[name=DataInicio]').value
    event_begin = new Date(event_begin)
    var target_date = new Date(event_begin).getTime();
    var dias, horas, minutos, segundos;
    var regressiva = document.getElementById("regressiva");

    setInterval(function() {

        var current_date = new Date().getTime();
        var segundos_f = (target_date - current_date) / 1000;

        dias = parseInt(segundos_f / 86400);
        segundos_f = segundos_f % 86400;

        horas = parseInt(segundos_f / 3600);
        segundos_f = segundos_f % 3600;

        minutos = parseInt(segundos_f / 60);
        segundos = parseInt(segundos_f % 60);

        document.getElementById('dia').innerHTML = dias;
        document.getElementById('hora').innerHTML = horas;
        document.getElementById('minuto').innerHTML = minutos;
        document.getElementById('segundo').innerHTML = segundos;


    }, 1000);
</script>
<div class="container">
    @if(session()->has('success'))
    <div class="alert alert-success col-12">
        {{ session()->get('success') }}
    </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger col-12">
        {{ session()->get('error') }}
    </div>
    @endif
    <section class="border-bottom border-dark">
        <div class="row height-menu">
            <div class="col-md-6 col-sm-12 align-self-center">
                <img class="d-flex img-fluid mt-2 pl-1 m-auto" src="{{ url("/storage/{$eventos->Logo}") }}">
            </div>
            <div class="align-self-center col-lg-6 col-md-6 col-sm-12 text-center hero-text">
                <strong>
                    <p class="">
                        {{$eventos->Nome}}
                    </p>
                </strong>
                <p class=""> Está Chegando!<br>
                    <span id="dia"></span> D : <span id="hora"></span> H: <span id="minuto"></span>M : <span id="segundo"></span>S
                </p>
                @if($eventos->inscrito == false)
                <h2 class="text-center"><a class="btn btn-success " href="{{ route('inscrever_user_evento',['idEvento' => $eventos->idEvento]) }}" role="button">Inscrever-se</a></h2>
                @else
                <h2 class="text-center"><a class="btn btn-danger " href="{{ route('desinscrever',['idEvento' => $eventos->idEvento]) }}" role="button">Desinscrever-se</a></h2>
                @endif
                <p class="lead text-center">
                    <strong>Inscrições até: {{ date("d/m/Y", strtotime($eventos->DataLimiteInscricao)) }}</strong>
                </p>
                <input type="hidden" name="DataInicio" value="{{$eventos->DataInicio}}">
            </div>
        </div>
    </section>
    <section class="about">
        <h1 class="text-center section-title"> Sobre </h1>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <p class="text-justify text-section align-self-center">
                    O evento <strong>{{$eventos->Nome}}</strong> será realizado no(s) dia(s) <strong>{{ date("d/m/Y", strtotime($eventos->DataInicio)) }} </strong> à <strong>{{ date("d/m/Y", strtotime($eventos->DataFim)) }}</strong> com um carga horária de <strong> {{$eventos->CargaHoraria}}</strong>, no local: <strong>{{$eventos->Local}}</strong>, às: <strong> {{$eventos->HorarioInicio}}</strong> até <strong> {{$eventos->HorarioFim}}</strong>
                    , organizado por: <strong>{{$eventos->Responsavel}}</strong>.
                </p>
            </div>
            <div class="col-sm-12 col-md-6 align-self-center mb-5">
                <img src="{{ asset('images/img-about.svg') }}" class="img-fluid">
            </div>
        </div>
    </section>
</div>
<section class="activity bg-purple">
    <div class="container">
        <h1 class="text-center section-title text-white"> Atividades </h1>
        <div class="row">
            @if (count($atividades) > 0)
            @foreach ($atividades as $atividade)
            <div class="col-12 col-sm-6  col-md-4">
                <div class="form-box">
                    @if ($atividade->CondicaoAtividade == "Ativado")
                    <div class="card-body bg-light mb-3 ml-1 mr-1">
                        <h4 class="card-title text-center"><?php echo ucfirst($atividade->nomeAtividade) ?></h4>
                        <hr id="list_hr">
                        <p class="text-center"><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <strong> Início: </strong> {{ date("d/m/Y", strtotime($atividade->DataInicio)) }} <strong> às </strong> {{$atividade->HoraInicio}} <strong> <br>Término: </strong>{{ date("d/m/Y", strtotime($atividade->DataTermino)) }} <strong> até</strong> {{$atividade->HoraTermino}} </small></p>
                        <div class="row text-center justify-content-center">
                            @if($atividade->inscrito == false)
                            <p class=""><a class="btn btn-success" href="{{ route('inscrever_user_atividade',['idAtividade' => $atividade->idAtividade, 'idEvento' => $eventos->idEvento]) }}" role="button">Inscrever-se</a></p>
                            @else
                            <p class=""><a class="btn btn-danger" href="{{ route('desinscrever_user_atividade',['idAtividade' => $atividade->idAtividade, 'idEvento' => $eventos->idEvento]) }}" role="button">Desinscrever-se</a></p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>
<div class="container">
    <section class="panelist">
        <h1 class="text-center section-title "> Palestrantes </h1>
        <div class="row">
            <div class="col-sm-12 col-md-6 align-self-center mb-5">
                <img src="{{ asset('images/img-panelist.svg')}}" class="img-fluid">
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="row">
                    @if (isset($palestrantes))
                    @foreach ($palestrantes as $palestrante)
                    <div class="col-sm-12 col-md-6 mb-2">
                        <div class="form-box">
                            <div class="card-body">
                                <h4 class="card-title text-center"><?php echo ucfirst($palestrante->name) ?></h4>
                                <hr id="list_hr">
                                <p class="text-center"><small class="text-muted"><i class="glyphicon glyphicon-time"></i><strong> Email: </strong>{{$palestrante->email}}</small></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @if($eventos->CondicaoCadastroDeAtividade == "Sim")
        @can("isParticipante")
        <div class="lead mx-auto col-lg-8 col-md-8 text-justify">
            <p><strong>Esse evento está aberto para receber atividades da comunidade, caso deseje participar, basta clicar no botão abaixo, preencher o formulário e aguardar a resposta em seu email.</strong></p>
            <div class="col-4 mx-auto my-1"><a class="btn btn-success" href="{{ route('showFormAtividade',['idEvento' => $eventos->idEvento]) }}" role="button">Enviar Proposta!</a></div>
        </div>
        @endcan
        @endif
    </section>
</div>

<section class="place bg-purple">
    <h1 class="text-center section-title text-white"> Local do Evento </h1>
    <div class="row">
        <div class=" col-sm-12 col-md-6 align-self-center mb-5 d-flex justify-content-center">
            <img src="{{ asset('images/img-event-locale.svg') }}" class="img-fluid img-local">
        </div>
        <div class="col-sm-12 col-md-6 event-adress text-section">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3656.2931471902366!2d-48.02073778506283!3d-23.593817384666867!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c5cbf41ebfcf31%3A0xa65e4fb6d75446bf!2sInstituto%20Federal%20de%20Educa%C3%A7%C3%A3o%2C%20Ci%C3%AAncia%20e%20Tecnologia%20de%20S%C3%A3o%20Paulo%2C%20Campus%20Itapetininga!5e0!3m2!1spt-BR!2sbr!4v1585933087747!5m2!1spt-BR!2sbr" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            <p class="text-white">{{$eventos->Local}}</p>
        </div>
    </div>
</section>
<section class="gallery-meetape">
    <h1 class="text-center section-title"> Galeria </h1>

</section>

@endsection
