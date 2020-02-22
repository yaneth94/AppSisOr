<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
//request personalizado
use App\Http\Requests\PacienteRequest;
use App\Http\Requests\PacienteRequestUpdate;
use App\Institucion;
use App\DetalleMenorEdad;
use App\Paciente;
use App\Telefono;
use App\AntecedenteMedico;
use App\AntecedenteOdontologico;
use App\AntecedenteOrtodoncico;
use App\DiagnosticoPrevio;
use App\FacialFrontal;
use App\PerfilPaciente;
use App\TejidoIntraoral;
use App\Denticion;
use App\LineaMedia;
use App\Mordida;
use App\RelacionSagital;
use App\Discrepancia;
use App\DientesPaciente;
use App\Cefalometrico;
use Carbon\Carbon;


class PacienteController extends Controller
{
    public function list(Request $request)
    {
        $pacientes = Paciente::orderby('id', 'DESC')
            ->paginate(5);

        return [
            'pagination' => [
                'total'         => $pacientes->total(),
                'current_page'  => $pacientes->currentPage(),
                'per_page'      => $pacientes->perPage(),
                'last_page'     => $pacientes->lastPage(),
                'from'          => $pacientes->firstItem(),
                'to'            => $pacientes->lastItem(),
            ],
            'pacientes' => $pacientes,
        ];
    }
    public function index()
    {
        return view('pacientes.index');
    }
    public function create()
    {
        return view('pacientes.create');
    }
    //Para calcular la Edad
    public function calcularEdad(Request $request)
    {
        $edad = Carbon::parse($request->fecha_nacimiento)->age;
        return $edad;
    }
    public function store(PacienteRequest $request)
    {
        $paciente = new Paciente;
        $paciente->nombre = $request->nombre;
        $paciente->fecha_nacimiento = $request->fechaNacimiento;
        $paciente->direccion = $request->direccion;
        $paciente->padecimiento = $request->padecimiento;
        $paciente->direccion_trabajo = $request->trabajo['direccionTrabajo'];
        $paciente->profesion = $request->trabajo['profesion'];
        $paciente->recomendacion = $request->recomendacion;
        $paciente->direccion_trabajo = $request->trabajo["direccionTrabajo"];
        $paciente->profesion = $request->trabajo["profesion"];
        $paciente->save();
        //telefonos
        $paciente->syncTelefonos($request->telefono);
        //estudiantes
        if ($request->estudia) {
            $paciente->institucion()->save(new Institucion(
                [
                    "carrera" => ($request->estudia["carrera"] ?? null),
                    "grado" => $request->estudia["grado"],
                    "nombre" => $request->estudia["nombreInstitucion"]
                ]
            ));
        }
        //encargados
        if ($request->encargados) {
            $paciente->detallesMenorEdad()->save(new DetalleMenorEdad(
                [
                    "madre" => ($request->encargados["nombreMadre"] ?? null),
                    "padre" => ($request->encargados["nombrePadre"] ?? null),
                    "ocupacion_madre" => ($request->encargados["ocupacionMadre"] ?? null),
                    "ocupacion_padre" => ($request->encargados["ocupacionPadre"] ?? null)
                ]
            ));
        }

        //*************Antecedentes*************
        $antecedente = $request->input("antecedente");
        //Antecendentes Medicos
        $antecedente_medico = new AntecedenteMedico;
        $antecedente_medico->saludAnio = $antecedente["medico"]["saludAnio"];
        $antecedente_medico->enfermedadOperacion = $antecedente["medico"]["enfermedadOperacion"];
        $antecedente_medico->alergia = $antecedente["medico"]["alergia"];
        $antecedente_medico->desmayo = $antecedente["medico"]["desmayo"];
        $antecedente_medico->sinusitis = $antecedente["medico"]["sinusitis"];
        $antecedente_medico->hepatitis = $antecedente["medico"]["hepatitis"];
        $antecedente_medico->asma = $antecedente["medico"]["asma"];
        $antecedente_medico->artritis = $antecedente["medico"]["artritis"];
        $antecedente_medico->diabetes = $antecedente["medico"]["diabetes"];
        $antecedente_medico->gastritis = $antecedente["medico"]["gastritis"];
        $antecedente_medico->renal = $antecedente["medico"]["renal"];
        $antecedente_medico->enfermedadVenerea = $antecedente["medico"]["enfermedadVenerea"];
        $antecedente_medico->tuberculosis = $antecedente["medico"]["tuberculosis"];
        $antecedente_medico->sida = $antecedente["medico"]["sida"];
        $antecedente_medico->presionAlta = $antecedente["medico"]["presionAlta"];
        $antecedente_medico->transtornoSangre = $antecedente["medico"]["transtornoSangre"];
        $antecedente_medico->tomaMedicamento = $antecedente["medico"]["tomaMedicamento"];
        $antecedente_medico->consumeMedicamento = $antecedente_medico->tomaMedicamento ? $antecedente["medico"]["consumeMedicamento"] : null;
        //Antecedentes Odontologicos
        $antecedente_odontologico = new AntecedenteOdontologico;
        $antecedente_odontologico->chequeDental = $antecedente["odontologico"]["chequeoDental"];
        $antecedente_odontologico->accidente = $antecedente["odontologico"]["accidente"];
        $antecedente_odontologico->habito = $antecedente["odontologico"]["habito"];
        //Antecedentes Ortodoncicos
        $antecedente_ortodoncico = new AntecedenteOrtodoncico;
        $antecedente_ortodoncico->primerVisita = $antecedente["ortodoncico"]["primerVisita"];
        $antecedente_ortodoncico->segundaOpinion = $antecedente["ortodoncico"]["segundaOpinion"];
        $antecedente_ortodoncico->tratamientoAnterior = $antecedente["ortodoncico"]["tratamientoAnterior"];
        $antecedente_ortodoncico->problemaFamiliar = $antecedente["ortodoncico"]["problemaFamiliar"];
        $antecedente_ortodoncico->esperaDeTratamiento = $antecedente["ortodoncico"]["esperaDeTratamiento"];

        $paciente->antecedenteMedico()->save($antecedente_medico);
        $paciente->antecedenteOdontologico()->save($antecedente_odontologico);
        $paciente->antecedenteOrtodoncico()->save($antecedente_ortodoncico);

        /****** Diagnostico Previo******/
        $diagnostico_previo = new DiagnosticoPrevio;
        $diagnostico_previo->descripcion = $request->diagnosticoPrevio['descripcionDiagnostico'];
        $diagnostico_previo->posible_tratamiento = $request->diagnosticoPrevio['planDeTratamiento'];
        $diagnostico_previo->necesidades_odontologicas = $request->diagnosticoPrevio['necesidadOdontologica'];

        $paciente->diagnosticoPrevio()->save($diagnostico_previo);

        //*************Ficha Ortodoncica*************
        $fichaDeOrtodoncia = $request->input("fichaDeOrtodoncia");
        //Facial frontal
        $facial_frontal = new FacialFrontal;
        $facial_frontal->facialFrontal = $fichaDeOrtodoncia["facialFrontal"]["frontal"];
        $facial_frontal->tercios = $fichaDeOrtodoncia["facialFrontal"]["tercios"];
        $facial_frontal->simetria = $fichaDeOrtodoncia["facialFrontal"]["simetria"];
        $facial_frontal->sonrisa = $fichaDeOrtodoncia["facialFrontal"]["sonrisa"];
        $facial_frontal->competencia = $fichaDeOrtodoncia["facialFrontal"]["competencia"];
        $paciente->facialFrontal()->save($facial_frontal);
        //Perfil
        $perfil_paciente = new PerfilPaciente;
        $perfil_paciente->perfilSuperior = $fichaDeOrtodoncia["perfil"]["perfilSuperior"];
        $perfil_paciente->perfilInferior = $fichaDeOrtodoncia["perfil"]["perfilInferior"];
        $perfil_paciente->anguloNasolabial = $fichaDeOrtodoncia["perfil"]["anguloNasolabial"];
        $perfil_paciente->nariz= $fichaDeOrtodoncia["perfil"]["nariz"];
        $perfil_paciente->labios = $fichaDeOrtodoncia["perfil"]["labios"];
        $paciente->perfilPaciente()->save($perfil_paciente);
        //Tejidos Intraorales
        $tejidos_intraorales = new TejidoIntraoral;
        $tejidos_intraorales->inspeccion = $fichaDeOrtodoncia["tejidosIntraorales"]["inspeccion"];
        $tejidos_intraorales->palpacion = $fichaDeOrtodoncia["tejidosIntraorales"]["palpacion"];
        $tejidos_intraorales->encias = $fichaDeOrtodoncia["tejidosIntraorales"]["encias"];
        $tejidos_intraorales->frenillos = $fichaDeOrtodoncia["tejidosIntraorales"]["frenillos"];
        $paciente->tejidoIntraoral()->save($tejidos_intraorales);
        //Denticion
        $denticion = new Denticion;
        $denticion->denticion = $fichaDeOrtodoncia["denticion"]["denticion"];
        $denticion->faltantes = $fichaDeOrtodoncia["denticion"]["faltantes"];
        $paciente->denticion()->save($denticion);
        // //Linea Medias
        $lineas_medias = new LineaMedia;
        $lineas_medias->maxilar = $fichaDeOrtodoncia["lineasMedias"]["maxilar"];
        $lineas_medias->mxDesviado = $fichaDeOrtodoncia["lineasMedias"]["mxDesviado"];
        $lineas_medias->mxCantidad = $fichaDeOrtodoncia["lineasMedias"]["mxCantidad"];
        $lineas_medias->mandibula = $fichaDeOrtodoncia["lineasMedias"]["mandibula"];
        $lineas_medias->mdDesviado = $fichaDeOrtodoncia["lineasMedias"]["mdDesviado"];
        $lineas_medias->mdCantidad = $fichaDeOrtodoncia["lineasMedias"]["mdCantidad"];
        $paciente->lineaMedia()->save($lineas_medias);
        //Mordidas
        $mordidas = new Mordida;
        $mordidas->sobreMordidaHorizontal = $fichaDeOrtodoncia["mordidas"]["horizontal"];
        $mordidas->sobreMordidadVertical = $fichaDeOrtodoncia["mordidas"]["vertical"];
        $mordidas->mordidasCruzadas = $fichaDeOrtodoncia["mordidas"]["mordidasCruzadas"];
        $paciente->mordida()->save($mordidas);
        //Relaciones Sagitales
        $relaciones_sagitales = new RelacionSagital;
        $relaciones_sagitales->molarDerecha = $fichaDeOrtodoncia["relacionesSagitales"]["molarDerecha"];
        $relaciones_sagitales->molarIzquierda = $fichaDeOrtodoncia["relacionesSagitales"]["molarIzquierda"];
        $relaciones_sagitales->caninaDerecha = $fichaDeOrtodoncia["relacionesSagitales"]["caninaDerecha"];
        $relaciones_sagitales->caninaIzquierda = $fichaDeOrtodoncia["relacionesSagitales"]["caninaIzquierda"];
        $paciente->relacionSagital()->save($relaciones_sagitales);
        //Analisis de espacio y discrepancia
        $espacio_discrepancia = new Discrepancia;
        $espacio_discrepancia->longitudArcoMx = $request->arcoMaxilar;
        $espacio_discrepancia->longitudArcoMd = $request->arcoMandibular;
        $espacio_discrepancia->boltonAnterior = $request->boltonAnterior;
        $espacio_discrepancia->boltonTotal = $request->boltonTotal;
        $paciente->espacioDiscrepancia()->save($espacio_discrepancia);
        //Dientes
        $paciente->syncDientes($request->dientesMaxilarDerecho);
        $paciente->syncDientes($request->dientesMaxilarIzquierdo);
        $paciente->syncDientes($request->dientesMandibulaDerecho);
        $paciente->syncDientes($request->dientesMandibulaIzquierdo);

        /****** Análisis Cefalométrico ******/
        $paciente->syncCefalometrico($request->cefalometrico);

        return response()->json(['respuesta' => 'Paciente Creado con Exito']);
    }
    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        $telefonos = $paciente->telefonos()->get()->all();
        $encargados = $paciente->detallesMenorEdad()->get()->first();
        $estudia = $paciente->institucion()->get()->first();
        $antecedente_medico = $paciente->antecedenteMedico()->get()->first();
        $antecedente_odontologico = $paciente->antecedenteOdontologico()->get()->first();
        $antecedente_ortodoncico = $paciente->antecedenteOrtodoncico()->get()->first();
        $diagnostico_previo = $paciente->diagnosticoPrevio()->get()->first();
        $cefalometricos = $paciente->cefalometricos()->get()->all();
        $edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        return view('pacientes.edit', compact(
            'paciente',
            'telefonos',
            'encargados',
            'estudia',
            'edad',
            'antecedente_medico',
            'antecedente_odontologico',
            'antecedente_ortodoncico',
            'diagnostico_previo',
            'cefalometricos'
        ));
    }
    public function update(PacienteRequestUpdate $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->nombre = $request->nombre;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->direccion = $request->direccion;
        $paciente->padecimiento = $request->padecimiento;
        $paciente->direccion_trabajo = $request->direccion_trabajo;
        $paciente->profesion = $request->profesion;
        $paciente->recomendacion = $request->recomendacion;
        $paciente->save();
        //consulta a la base
        $telefonosConsulta = Telefono::where('paciente_id', $id)->delete();
        for ($i = 0; $i < 3; $i++) {
            if (!is_null($request->telefono[$i])) {
                $telefono = new Telefono;
                $telefono->telefono = $request->telefono[$i];
                $telefono->paciente_id = $id;
                $telefono->save();
            }
        }
        if ($request->grado && $request->nombre_institucion) {
            $institucion = Institucion::where('paciente_id', $id)->delete();
            $paciente->institucion()->save(new Institucion(
                [
                    "carrera" => ($request->carrera ?? null),
                    "grado" => $request->grado,
                    "nombre" => $request->nombre_institucion
                ]
            ));
        }

        $menores = DetalleMenorEdad::where('paciente_id', $id)->delete();
        $paciente->detallesMenorEdad()->save(new DetalleMenorEdad(
            [
                "madre" => ($request->madre ?? null),
                "padre" => ($request->padre ?? null),
                "ocupacion_madre" => ($request->ocupacion_madre ?? null),
                "ocupacion_padre" => ($request->ocupacion_padre ?? null)
            ]
        ));
        //***** Antecedentes ******/
        //Antecedente Medico
        $antecedente_medico_actual = AntecedenteMedico::where('paciente_id', $id)->delete();
        $antecedente_medico = new AntecedenteMedico;
        $antecedente_medico->saludAnio = $request->saludAnio;
        $antecedente_medico->enfermedadOperacion = $request->enfermedadOperacion;
        $antecedente_medico->alergia = $request->alergia;
        $antecedente_medico->desmayo = $request->desmayo;
        $antecedente_medico->sinusitis = $request->sinusitis;
        $antecedente_medico->hepatitis = $request->hepatitis;
        $antecedente_medico->asma = $request->asma;
        $antecedente_medico->artritis = $request->artritis;
        $antecedente_medico->diabetes = $request->diabetes;
        $antecedente_medico->gastritis = $request->gastritis;
        $antecedente_medico->renal = $request->renal;
        $antecedente_medico->enfermedadVenerea = $request->enfermedadVenerea;
        $antecedente_medico->tuberculosis = $request->tuberculosis;
        $antecedente_medico->sida = $request->sida;
        $antecedente_medico->presionAlta = $request->presionAlta;
        $antecedente_medico->transtornoSangre = $request->transtornoSangre;
        $antecedente_medico->tomaMedicamento = $request->tomaMedicamento;
        $antecedente_medico->consumeMedicamento = ($antecedente_medico->consumeMedicamento ? $request->consumeMedicamento : null);
        $paciente->antecedenteMedico()->save($antecedente_medico);

        //Antecedente Odontologico
        $antecedente_odontologico_actual = AntecedenteOdontologico::where('paciente_id', $id)->delete();
        $antecedente_odontologico = new AntecedenteOdontologico;
        $antecedente_odontologico->chequeDental = $request->chequeoDental;
        $antecedente_odontologico->accidente = $request->accidente;
        $antecedente_odontologico->habito = $request->habito;
        $paciente->antecedenteOdontologico()->save($antecedente_odontologico);

        //Antecedente Ortodoncio
        $antecedente_ortodoncico_actual = AntecedenteOrtodoncico::where('paciente_id', $id)->delete();
        $antecedente_ortodoncico = new AntecedenteOrtodoncico;
        $antecedente_ortodoncico->primerVisita = $request->primerVisita;
        $antecedente_ortodoncico->segundaOpinion = $request->segundaOpinion;
        $antecedente_ortodoncico->tratamientoAnterior = $request->tratamientoAnterior;
        $antecedente_ortodoncico->problemaFamiliar = $request->problemaFamiliar;
        $antecedente_ortodoncico->esperaDeTratamiento = $request->esperaDeTratamiento;
        $paciente->antecedenteOrtodoncico()->save($antecedente_ortodoncico);

        //Diagnostico Previo
        $diagnostico_previo_actual = DiagnosticoPrevio::where('paciente_id', $id)->delete();
        $diagnostico_previo = new DiagnosticoPrevio;
        $diagnostico_previo->descripcion = $request->descripcionDiagnostico;
        $diagnostico_previo->posible_tratamiento = $request->planDeTratamiento;
        $diagnostico_previo->necesidades_odontologicas = $request->necesidadOdontologica;
        $paciente->diagnosticoPrevio()->save($diagnostico_previo);

        //Análisis Cefalometrico
        $cefalometricos = Cefalometrico::where('paciente_id', $id)->delete();
        //$paciente->syncCefalometrico($request->cefalometrico);

        return redirect('/pacientes')->with(['msj' => 'Paciente modificado con exito ']);
    }
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        $telefonos = $paciente->telefonos()->get()->all();
        $encargados = $paciente->detallesMenorEdad()->get()->first();
        $estudia = $paciente->institucion()->get()->first();
        $antecedente_medico = $paciente->antecedenteMedico()->get()->first();
        $antecedente_odontologico = $paciente->antecedenteOdontologico()->get()->first();
        $antecedente_ortodoncico = $paciente->antecedenteOrtodoncico()->get()->first();
        $diagnostico_previo = $paciente->diagnosticoPrevio()->get()->first();
        $facial_frontal = $paciente->facialFrontal()->get()->first();
        $perfil_paciente = $paciente->perfilPaciente()->get()->first();
        $tejidos_intraorales = $paciente->tejidoIntraoral()->get()->first();
        $denticion = $paciente->denticion()->get()->first();
        $lineas_medias = $paciente->lineaMedia()->get()->first();
        $mordidas = $paciente->mordida()->get()->first();
        $relaciones_sagitales = $paciente->relacionSagital()->get()->first();
        $espacio_discrepancia = $paciente->espacioDiscrepancia()->get()->first();
        $cefalometricos = $paciente->cefalometricos()->get()->all();
        $edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        return view('pacientes.show', compact(
            'paciente',
            'telefonos',
            'encargados',
            'estudia',
            'edad',
            'antecedente_medico',
            'antecedente_odontologico',
            'antecedente_ortodoncico',
            'diagnostico_previo',
            'facial_frontal',
            'perfil_paciente',
            'tejidos_intraorales',
            'denticion',
            'lineas_medias',
            'mordidas',
            'relaciones_sagitales',
            'espacio_discrepancia',
            'cefalometricos'
        ));
    }
}
