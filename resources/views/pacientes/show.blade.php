@extends('layouts.templateHome')

@section('titulo')
Ver Expediente
@endsection

@section('title_content')
<h1><i class="fa fa-dashboard"></i>Ver Expediente</h1>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('pacientes.index')}}">Pacientes</a></li>
<li class="breadcrumb-item"><a href="{{ route('pacientes.show', ['id'=> old('id')??$paciente->id]) }}">Mostrar
    Paciente</a></li>
@endsection


@section('content')
<div class="tile-body">
  <div class="row user">
    <div class="col-md-3">
      <div class="tile p-0">
        <ul class="nav flex-column nav-tabs user-tabs">
          <li class="nav-item"><a class="nav-link active" href="#detalle-expediente" data-toggle="tab">Detalle
              Expediente</a></li>
          <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Citas</a></li>
          <li class="nav-item"><a class="nav-link" href="#pagos" data-toggle="tab">Pagos</a></li>
        </ul>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content">
        <div class="tab-pane active" id="detalle-expediente">
          <div class="timeline-post">
            <h3 class="mb-3 text-primary">Información Personal</h3>
            <p><strong>Nombre Completo:</strong> <span>{{ old('nombre')??$paciente->nombre}}</span></p>
            <p><strong>Dirección Residencial:</strong> {{ old('direccion')??$paciente->direccion}}</p>
            <p><strong>Edad:</strong> {{ old('edad')??$edad}}</p>
            <p><strong>Telefonos:</strong>
              @foreach($telefonos as $telefono)
              <ul>
                <li>{{$telefono->telefono}}</li>
              </ul>
              @endforeach
            </p>
            <p><strong>Fecha de Nacimiento:</strong>
              <span>{{ old('fecha_nacimiento')??$paciente->fecha_nacimiento}}</span></p>
            <p><strong>Recomendación:</strong>
              @if($paciente->recomendacion)
              <span>{{ old('recomendacion')??$paciente->recomendacion}}</span>
              @else
              <span>No tiene recomendación</span>
              @endif
            </p>
          </div>
          @if($paciente->direccion_trabajo)
          <div class="timeline-post">
            <h3 class="mb-3 text-primary">Información Laboral</h3>
            <p><strong>Dirección:</strong> <span>{{ old('direccion_trabajo')??$paciente->direccion_trabajo}}</span></p>
            <p><strong>Profesión:</strong> {{ old('profesion')??$paciente->profesion}}</p>
          </div>
          @endif
          <div class="timeline-post">
            <h3 class="mb-3 text-primary">Antecedentes Médicos</h3>
            <div class="row">
              <div class="col-md-6">
                <p>Hubo un cambio grave de salud en el ultimo año: <span
                    class="font-weight-bold">{{ $antecedente_medico->saludAnio ? 'SI':'NO'}}</span></p>
              </div>
              <div class="col-md-6">
                <p>Ha tenido alguna vez una enfermedad u operacion grave <span
                    class="font-weight-bold">{{ $antecedente_medico->enfermedadOperacion ? 'SI':'NO'}}</span></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <p>Padeció: Alergia <span class="font-weight-bold">{{$antecedente_medico->alergia ? 'SI':'NO'}}</span>
                </p>
                <p>Padeció: Desmayos <span class="font-weight-bold">{{$antecedente_medico->desmayo ? 'SI':'NO'}}</span>
                </p>
              </div>
              <div class="col-md-3">
                <p>Padeció: Sinusitis <span
                    class="font-weight-bold">{{$antecedente_medico->sinusitis ? 'SI':'NO'}}</span></p>
                <p>Padeció: Hepatitis <span
                    class="font-weight-bold">{{$antecedente_medico->hepatitis ? 'SI':'NO'}}</span></p>
              </div>
              <div class="col-md-3">
                <p>Padeció: Asma <span class="font-weight-bold">{{$antecedente_medico->asma ? 'SI':'NO'}}</span></p>
                <p>Padeció: Artristis <span
                    class="font-weight-bold">{{$antecedente_medico->artritis ? 'SI':'NO'}}</span></p>
              </div>
              <div class="col-md-3">
                <p>Padeció: Diabetes <span class="font-weight-bold">{{$antecedente_medico->diabetes ? 'SI':'NO'}}</span>
                </p>
                <p>Padeció: Gastritis <span
                    class="font-weight-bold">{{$antecedente_medico->gastritis ? 'SI':'NO'}}</span></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <p>Padeció: Transtornos Renales <span
                    class="font-weight-bold">{{$antecedente_medico->renal ? 'SI':'NO'}}</span></p>
                <p>Padeció: Enfermedades Venereas <span
                    class="font-weight-bold">{{$antecedente_medico->enfermedadVenerea ? 'SI':'NO'}}</span></p>
              </div>
              <div class="col-md-4">
                <p>Padeció: Tuberculosis <span
                    class="font-weight-bold">{{$antecedente_medico->tuberculosis ? 'SI':'NO'}}</span></p>
                <p>Padeció: SIDA <span class="font-weight-bold">{{$antecedente_medico->sida ? 'SI':'NO'}}</span></p>
              </div>
              <div class="col-md-4">
                <p>Padeció: Presión Sanguinea Alta <span
                    class="font-weight-bold">{{$antecedente_medico->presionAlta ? 'SI':'NO'}}</span></p>
                <p>Padeció: Transtorno de la Sangre <span
                    class="font-weight-bold">{{$antecedente_medico->transtornoSangre ? 'SI':'NO'}}</span></p>
                <p>Toma algún medicamento: <span
                    class="font-weight-bold">{{$antecedente_medico->tomaMedicamento ? 'SI':'NO'}}</span></p>
              </div>
            </div>
            @if($antecedente_medico->tomaMedicamento)
            <div class="row">
              <div class="col">
                <p class="text-center font-weight-bold">Descripción del medicamento</p>
                <p class="text-justify">{{$antecedente_medico->consumeMedicamento}}</p>
              </div>
            </div>
            @endif

          </div>
          <div class="timeline-post">
            <h3 class="mb-3 text-primary">Antecedentes Odontologicos</h3>
            <div class="row">
              <div class="col-12">
                <p>Hace cuánto tiempo se hizo su último chequeo Dental?</p>
                <p class="text-center font-weight-bold">{{$antecedente_odontologico->chequeDental}}</p>
              </div>
              <div class="col-12">
                <p>Ha tenido alguna vez algún accidente que involucre sus dientes, cara o boca ?</p>
                <p class="text-center font-weight-bold">{{$antecedente_odontologico->accidente}}</p>
              </div>
              <div class="col-12">
                <p>Tiene algún hábito que involucre sus dientes o boca ?</p>
                <p class="text-center font-weight-bold">{{$antecedente_odontologico->habito}}</p>
              </div>
            </div>
          </div>
          <div class="timeline-post">
            <h3 class="mb-3 text-primary">Antecedentes Ortodoncicos</h3>
            <div class="row">
              <div class="col-12">
                <p>Es la primer visita a un ortodoncista?: <span
                    class="font-weight-bold">{{$antecedente_ortodoncico->primerVisita ? 'SI':'NO'}}</p>
              </div>
              <div class="col-12">
                <p>Ya ha tenido contacto con un ortodoncista pero necesita otra opinión?: <span
                    class="font-weight-bold">{{$antecedente_ortodoncico->segundaOpinion ? 'SI':'NO'}}</p>
              </div>
              <div class="col-12">
                <p>Ya tuvo anteriormente tratamiento de ortodoncia?: <span
                    class="font-weight-bold">{{$antecedente_ortodoncico->tratamientoAnterior ? 'SI':'NO'}}</p>
              </div>
              <div class="col-12">
                <p>Hay otro miembros de la familia que presentan problema similar?: <span
                    class="font-weight-bold">{{$antecedente_ortodoncico->problemaFamiliar ? 'SI':'NO'}}</p>
              </div>
              <div class="col-12">
                <p>Que espera del tratamiento de ortodoncia?: <span
                    class="font-weight-bold">{{$antecedente_ortodoncico->esperaDeTratamiento}}</p>
              </div>
            </div>
          </div>
          @if($edad<18) <div class="timeline-post">
            <h3 class="mb-3 text-primary">Información de Menores de Edad</h3>
            @if($encargados->madre)
            <p><strong>Nombre de la Madre:</strong> <span>{{ old('madre')??$encargados->madre}}</span></p>
            <p><strong>Ocupacion de la Madre:</strong>
              <span>{{ old('ocupacion_madre')??$encargados->ocupacion_madre}}</span></p>
            @endif
            @if($encargados->padre)
            <p><strong>Nombre del Padre:</strong> <span>{{ old('padre')??$encargados->padre}}</span></p>
            <p><strong>Ocupacion del Padre:</strong>
              <span>{{ old('ocupacion_padre')??$encargados->ocupacion_padre}}</span></p>
            @endif
            @endif
            @if(!is_null($estudia))
            <div class="timeline-post">
              <h3 class="mb-3 text-primary">Información Escolar</h3>
              <p><strong>Nombre de la Institución:</strong> <span>{{ old('nombre')??$estudia->nombre}}</span></p>
              <p><strong>Grado que cursa:</strong> <span>{{ old('grado')??$estudia->grado}}</span></p>
              @if($paciente->carrera)
              <p><strong>Carrera:</strong> {{ old('carrera')??$paciente->carrera}}</p>
              @endif
            </div>
            @endif
            <div class="timeline-post">
              <h3 class="mb-3 text-primary">Diagnostico Previo</h3>
              <p><strong>Descripción de Diagnostico</strong> <span>{{$diagnostico_previo->descripcion}}</span></p>
              <p><strong>Posible Tratamiento</strong> <span>{{$diagnostico_previo->posible_tratamiento}}</span></p>
              <p><strong>Necesidades </strong> <span>{{$diagnostico_previo->necesidades_odontologicas}}</span></p>
            </div>
            <div class="timeline-post">
              <h3 class="mb-3 text-primary">Ficha de Ortodoncia</h3>
              <div class="row">
                <div class="col-12 col-md-3 text-justify">
                  <p class="font-weight-bold">Facial Frontal</p>
                  <p>Facial Frontal: <span class="font-weight-bold">{{$facial_frontal->facialFrontal}}</span></p>
                  <p>Tercios: <span class="font-weight-bold">{{$facial_frontal->tercios}}</span></p>
                  <p>Sonrisa: <span class="font-weight-bold">{{$facial_frontal->sonrisa}}</span></p>
                  <p> Simetria: <span class="font-weight-bold">{{$facial_frontal->simetria ? 'SI':'NO'}}</span></p>
                  <p> Competencia: <span class="font-weight-bold">{{$facial_frontal->competencia ? 'SI':'NO'}}</span></p>
                </div>
                <div class="col-12 col-md-3 text-justify">
                  <p class="font-weight-bold">Perfil</p>
                  <p>Perfil Superior: <span class="font-weight-bold">{{$perfil_paciente->perfilSuperior}}</span></p>
                  <p>Perfil Inferior: <span class="font-weight-bold">{{$perfil_paciente->perfilInferior}}</span></p>
                  <p>Angulo Nasolabial: <span class="font-weight-bold">{{$perfil_paciente->anguloNasolabial}}</span></p>
                  <p>Nariz <span class="font-weight-bold">{{$perfil_paciente->nariz}}</span></p>
                  <p>Labios <span class="font-weight-bold">{{$perfil_paciente->labios}}</span></p>
                </div>
                <div class="col-12 col-md-3 text-justify">
                  <p class="font-weight-bold">Denticion</p>
                  <p>Denticion: <span class="font-weight-bold">{{$denticion->denticion}}</span></p>
                  <p>Faltantes: <span class="font-weight-bold">{{$denticion->faltantes}}</span></p>
                </div>
                <div class="col-12 col-md-3 text-justify">
                  <p class="font-weight-bold">Lineas Medias</p>
                  @if ($lineas_medias->maxilar == "normal")
                    <p>Maxilar: <span class="font-weight-bold">{{$lineas_medias->maxilar}}</span></p>
                  @else
                    <p>Maxilar: <span class="font-weight-bold">{{$lineas_medias->maxilar}}</span></p>
                    <p>Desviado a la: <span class="font-weight-bold">{{$lineas_medias->mxDesviado}}</span></p>
                    <p>Con: <span class="font-weight-bold">{{$lineas_medias->mxCantidad}}  mm</span></p>
                  @endif
                  @if ($lineas_medias->mandibula == "normal")
                  <p>Mandibula: <span class="font-weight-bold">{{$lineas_medias->mandibula}}</span></p>
                  @else
                  <p>Mandibula: <span class="font-weight-bold">{{$lineas_medias->mandibula}}</span></p>
                  <p>Desviado a la: <span class="font-weight-bold">{{$lineas_medias->mdDesviado}}</span></p>
                  <p>Con: <span class="font-weight-bold">{{$lineas_medias->mdCantidad}}  mm</span></p>
                  @endif
                </div>
              </div>
              <div class="row">
                  <div class="col-12 col-md-3 text-justify">
                    <p class="font-weight-bold">Tejidos Intraorales</p>
                    <p>Inspección: <span class="font-weight-bold">{{$tejidos_intraorales->inspeccion}}</span></p>
                    <p>Palpación: <span class="font-weight-bold">{{$tejidos_intraorales->palpacion}}</span></p>
                    <p>Encías: <span class="font-weight-bold">{{$tejidos_intraorales->encias}}</span></p>
                    <p>Frenillos: <span class="font-weight-bold">{{$tejidos_intraorales->frenillos}}</span></p>
                  </div>
                  <div class="col-12 col-md-3 text-justify">
                    <p class="font-weight-bold">Mordidas</p>
                    <p>Sobre mordida horizontal: <span class="font-weight-bold">{{$mordidas->sobreMordidaHorizontal}} mm</span></p>
                    <p>Sobre mordida vertical: <span class="font-weight-bold">{{$mordidas->sobreMordidadVertical}} mm</span></p>
                    <p>Mordidas Cruzadas: <span class="font-weight-bold">{{$mordidas->mordidasCruzadas}} mm</span></p>
                  </div>
                  <div class="col-12 col-md-3 text-justify">
                    <p class="font-weight-bold">Relaciones Sagitales</p>
                    <p>Molar Derecha: <span class="font-weight-bold">{{$relaciones_sagitales->molarDerecha}}</span></p>
                    <p>Molar Izquierda: <span class="font-weight-bold">{{$relaciones_sagitales->molarIzquierda}}</span></p>
                    <p>Canina Derecha: <span class="font-weight-bold">{{$relaciones_sagitales->caninaDerecha}}</span></p>
                    <p>Canina Izquierda: <span class="font-weight-bold">{{$relaciones_sagitales->caninaIzquierda}}</span></p>
                  </div>
                  <div class="col-12 col-md-3 text-justify">
                    <p class="font-weight-bold">Analisis de Espacio y Discrepancia</p>
                    <p>Arco Maxilar: <span class="font-weight-bold">{{$espacio_discrepancia->longitudArcoMx}} mm</span></p>
                    <p>Arco Mandibular: <span class="font-weight-bold">{{$espacio_discrepancia->longitudArcoMd}} mm</span></p>
                    <p>Bolton Anterior: <span class="font-weight-bold">{{$espacio_discrepancia->boltonAnterior}}</span></p>
                    <p>Bolton Total: <span class="font-weight-bold">{{$espacio_discrepancia->boltonTotal}}</span></p>
                  </div>
                  <div class="card border-primary mb-3">
                    <div class="card-header text-white bg-primary">Análisis Cefalométrico</div>
                    <div class="card-body ">
                        <div class="form-group ">
                            <div class="row">
                                <div class="col">
                                    <div class="col-12 col-md-5">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="text-center">Nombre Análisis</th>
                                                    <th class="text-center">Valor</th>
                                                    <th class="text-center">Interpretación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--<tr ="cefalometrico in paciente.cefalometrico">
                                                    <td> <span v-text="cefalometrico.nombre"></span></td>
                                                    <td>
                                                        <input class="form-control" type="number" name="cefalometrico[]"
                                                            value="">
                                                    </td>
                                                    <td><span v-text="cefalometrico->valor < cefalometrico.valorRegular ? cefalometrico.menor :
                                                        (cefalometrico->valor > cefalometrico.valorRegular ? cefalometrico->mayor : cefalometrico.normal) "></span></td>
                                                </tr>-->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
        </div>
        <div class="tab-pane fade" id="user-settings">
          <div class="tile user-settings">
            <h4 class="line-head">Settings</h4>
            <form>
              <div class="row mb-4">
                <div class="col-md-4">
                  <label>First Name</label>
                  <input class="form-control" type="text">
                </div>
                <div class="col-md-4">
                  <label>Last Name</label>
                  <input class="form-control" type="text">
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 mb-4">
                  <label>Email</label>
                  <input class="form-control" type="text">
                </div>
                <div class="clearfix"></div>
                <div class="col-md-8 mb-4">
                  <label>Mobile No</label>
                  <input class="form-control" type="text">
                </div>
                <div class="clearfix"></div>
                <div class="col-md-8 mb-4">
                  <label>Office Phone</label>
                  <input class="form-control" type="text">
                </div>
                <div class="clearfix"></div>
                <div class="col-md-8 mb-4">
                  <label>Home Phone</label>
                  <input class="form-control" type="text">
                </div>
              </div>
              <div class="row mb-10">
                <div class="col-md-12">
                  <button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>
                    Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="tab-pane fade" id="pagos">
          <div class="timeline-post">

            <div class="d-flex justify-content-between mb-3">
              <h3 class="mb-3 text-primary">Información Pagos</h3>
              <a class="btn btn-outline-success" href=""><i class="fa fa-plus icon-expe"></i>Registrar</a>
            </div>
            <h5 class="mb-3 text-center"> Nombre del paciente: {{ old('nombre')??$paciente->nombre}} </h5>
            <div class="card border-info mb-3" style="width:100%">
              <div class="card-header d-flex justify-content-between ">
                <p>Tratamiento: Ortodoncia</p>
                <div class="botones">
                  <a class="btn btn-outline-info mr-2"><i class="fa fa-pencil icon-expe"></i></a>
                  <a class="btn btn-outline-primary mr-2"><i class="fa fa-eye icon-expe"></i></a>
                </div>
              </div>
              <div class="card-body">
                <p class="card-text">Descripción: Tiene un tratamiento</p>
                <p class="card-text">Total: $ 95.00</p>
              </div>
            </div>
            <div class="card border-info mb-3" style="width:100%">
              <div class="card-header d-flex justify-content-between ">
                <p>Tratamiento: Ortodoncia</p>
                <div class="botones">
                  <a class="btn btn-outline-info mr-2"><i class="fa fa-pencil icon-expe"></i></a>
                  <a class="btn btn-outline-primary mr-2"><i class="fa fa-eye icon-expe"></i></a>
                </div>
              </div>
              <div class="card-body">
                <p class="card-text">Descripción: Tiene un tratamiento</p>
                <p class="card-text">Total: $ 95.00</p>
              </div>
            </div>
            <div class="card border-info mb-3" style="width:100%">
              <div class="card-header d-flex justify-content-between ">
                <p>Tratamiento: Ortodoncia</p>
                <div class="botones">
                  <a class="btn btn-outline-info mr-2"><i class="fa fa-pencil icon-expe"></i></a>
                  <a class="btn btn-outline-primary mr-2"><i class="fa fa-eye icon-expe"></i></a>
                </div>
              </div>
              <div class="card-body">
                <p class="card-text">Descripción: Tiene un tratamiento</p>
                <p class="card-text">Total: $ 95.00</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('custom_javas')

@endsection
