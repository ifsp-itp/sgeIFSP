<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventoModel extends Model
{
    
    protected   $table          = 'evento';
    public      $timestamps     = false;
    protected   $fillable       = array('CondicaoEvento','CondicaoCadastroDeAtividade','Nome','Apelido','DataInicio','DataFim','DataLimiteInscricao','ConteudoProgramatico','Responsavel','CargaHoraria','HorarioInicio','HorarioFim','Local','Logo','Site', 'idTemplate');
    protected   $primaryKey = 'idEvento';
    protected   $guarded        = ['idEvento'];
    
}
