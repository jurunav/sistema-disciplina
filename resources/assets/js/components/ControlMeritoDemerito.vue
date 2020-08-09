<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo Formulario -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Formulario Control de Meritos y Demeritos
                </div>
                <div class="card-body">
                    <div v-if="errorFormulario" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMsj" :key="error" v-text="error"></div>
                        </div>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Cadete</label>
                                            <v-select
                                                    @search="selectCadete"
                                                    label="nombre"
                                                    :options="arrayCadete"
                                                    placeholder="Buscar Cadete..."
                                                    @input="getDatosCadete"
                                                    :value="cadete"
                                            >
                                            </v-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-control-label">Fecha</label>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Fecha Inicio</label>
                                                    <input type="datetime-local" v-model="fecha_inicio" class="form-control" placeholder="Fecha Inicio">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Fecha Fin</label>
                                                    <input type="datetime-local" v-model="fecha_fin" class="form-control" placeholder="Fecha Fin">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Jefe de Sección</label>
                                            <v-select
                                                    @search="selectJefeDeSeccion"
                                                    label="nombre"
                                                    :options="arrayJefeDeSeccion"
                                                    placeholder="Buscar Jefe de Seccion..."
                                                    @input="getDatosJefeDeSeccion"
                                                    :value="jefeDeSeccion"
                                            >
                                            </v-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Comandante Escuadron</label>
                                            <v-select
                                                    @search="selectComandanteEscuadron"
                                                    label="nombre"
                                                    :options="arrayComandanteEscuadron"
                                                    placeholder="Buscar Comandante Escuadron..."
                                                    @input="getDatosComandanteEscuadron"
                                                    :value="comandanteEscuadron"
                                            >
                                            </v-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" @click="downloadReport()">Descargar Reporte</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Fin ejemplo Formulario-->
        </div>
    </main>
</template>

<script>
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';

    export default {
        data (){
            return {
                cadete : {},
                jefeDeSeccion : {},
                comandanteEscuadron : {},
                fecha_inicio : null,
                fecha_fin : null,
                errorFormulario : 0,
                arrayCadete : [],
                arrayJefeDeSeccion : [],
                arrayComandanteEscuadron : [],
                errorMsj : [],
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            }
        },
        methods : {
            downloadReport (){
                if (this.validarFormulario()){
                    return;
                }

                var filters = {
                    cadeteId: this.cadete.id,
                    startDate: this.fecha_inicio,
                    endDate: this.fecha_fin,
                    jefeDeSeccionId: this.jefeDeSeccion.id,
                    comandanteEscuadronId: this.comandanteEscuadron.id
                };

                location.href = '/report/control-merito-demerito?filters='+JSON.stringify(filters);
            },
            selectCadete(search){
                let me=this;
                axios({
                    url: '/api/cadetes/search',
                    method: 'GET',
                    params: {
                        limit: 10,
                        search: search
                    }
                }).then(function (response) {
                    let respuesta = response.data.results;
                    q: search;
                    me.arrayCadete = respuesta;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCadete(val1){
                let me = this;
                me.cadete = val1;
            },
            selectJefeDeSeccion(search){
                let me=this;
                axios({
                    url: '/api/personas',
                    method: 'GET',
                    params: {
                        limit: 10,
                        search: search,
                        criterio: 'nombre',
                        filters: {
                            withCadeteOnly: true,
                            finalStage: true,
                            withEncargadoOnly: true
                        }
                    },
                    paramsSerializer: function (params) {
                        return qs.stringify(params, {arrayFormat: 'brackets'})
                    },
                }).then(function (response) {
                    let respuesta = response.data.results;
                    q: search;
                    me.arrayJefeDeSeccion = respuesta.personas.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDatosJefeDeSeccion(val1){
                let me = this;
                me.jefeDeSeccion = val1;
            },
            selectComandanteEscuadron(search){
                let me=this;
                axios({
                    url: '/api/personas',
                    method: 'GET',
                    params: {
                        limit: 10,
                        search: search,
                        criterio: 'nombre',
                        filters: {
                            withCadeteOnly: true,
                            finalStage: true,
                            withEncargadoOnly: true
                        }
                    },
                    paramsSerializer: function (params) {
                        return qs.stringify(params, {arrayFormat: 'brackets'})
                    },
                }).then(function (response) {
                    let respuesta = response.data.results;
                    q: search;
                    me.arrayComandanteEscuadron = respuesta.personas.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDatosComandanteEscuadron(val1){
                let me = this;
                me.comandanteEscuadron = val1;
            },
            validarFormulario(){
                this.errorFormulario=0;
                this.errorMsj =[];

                if (!this.cadete) this.errorMsj.push("Seleccione un Cadete");
                if (!this.jefeDeSeccion) this.errorMsj.push("Seleccione un Jefe de Seccion");
                if (!this.comandanteEscuadron) this.errorMsj.push("Seleccione un Comandante Escuadron");

                if (this.fecha_inicio === null && this.fecha_fin === null)
                    this.errorMsj.push("Seleccione una fecha inicio y una fecha fin");

                if (this.errorMsj.length) this.errorFormulario = 1;

                return this.errorFormulario;
            },
        },
        mounted() {
            this.selectCadete();
            this.selectJefeDeSeccion();
            this.selectComandanteEscuadron();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
