<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Dedemeritos
                    <button type="button" @click="abrirModal('demerito','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="num_orden">Numero de Orden</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarDemerito(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarDemerito(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Num Orden</th>
                            <th>Cadete</th>
                            <th>Sanción</th>
                            <th>Sancionador</th>
                            <th>Cant Dia</th>
                            <th>Oficial/Encargado</th>
                            <th>Creado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="demerito in arrayDemerito" :key="demerito.id">
                            <td>
                                <button type="button" @click="abrirModal('demerito','actualizar',demerito)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="demerito.id">
                                    <button type="button" class="btn btn-danger btn-sm" @click="eliminarDemerito(demerito.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                            </td>
                            <td> {{ demerito.num_orden }}</td>
                            <td> {{ demerito.cadete.persona.nombre }}</td>
                            <td> {{ demerito.sancion.nombre }}</td>
                            <td> {{ demerito.sancionador.nombre }}</td>
                            <td> {{ demerito.cant_dia }}</td>
                            <td> {{ demerito.encargado.persona.nombre }}</td>
                            <td> {{ demerito.created_at }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Cadetes</label>
                                <div class="col-md-9">
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
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Sanciones</label>
                                <div class="col-md-9">
                                    <v-select
                                            @search="selectSancion"
                                            label="nombre"
                                            :options="arraySancion"
                                            placeholder="Buscar Sancion..."
                                            @input="getSancion"
                                            :value="sancion"
                                    >
                                    </v-select>
                                </div>
                            </div>

                            <div class="form-group row" v-if="esExtraordinario">
                                <label class="col-md-3 form-control-label" for="text-input">Cantidad Dia</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="cant_dia" class="form-control" placeholder="Cantidad dias">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Sancionador</label>
                                <div class="col-md-9">
                                    <v-select
                                            @search="selectSancionador"
                                            label="nombre"
                                            :options="arraySancionador"
                                            placeholder="Buscar Sancionador..."
                                            @input="getDatosSancionador"
                                            :value="sancionador"
                                    >
                                    </v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Num Orden</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_orden" class="form-control" placeholder="Numero de Orden">
                                </div>
                            </div>

                            <div v-show="errorDemerito" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjDemerito" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDemerito()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDemerito()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';

    export default {
        data (){
            return {
                demerito_id: 0,
                cadete : {},
                sancion :  {},
                sancionador :  {},
                num_orden : '',
                cant_dia : 0,
                arrayDemerito : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDemerito : 0,
                errorMostrarMsjDemerito : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : '',
                buscar : '',
                arrayCadete :[],
                arraySancion :[],
                arraySancionador :[],
                esExtraordinario: false
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {
            listarDemerito (page,buscar,criterio){
                let me=this;
                var data = {
                    page: page,
                    buscar: buscar,
                    criterio: criterio
                };

                axios({
                    url: '/api/demeritos',
                    method: 'GET',
                    params: data
                }).then(function (response) {
                    var respuesta= response.data.results;
                    me.arrayDemerito = respuesta.demeritos.data;
                    me.pagination= respuesta.pagination;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            selectSancion(search){
                let me=this;
                axios({
                    url: '/api/sanciones',
                    method: 'GET',
                    params: {
                        limit: 10,
                        search:search
                    }
                }).then(function (response) {
                    let respuesta = response.data.results;
                    q: search;
                    me.arraySancion = respuesta.sanciones.data;
                }).catch(function (error) {
                    console.log(error);
                });
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
            selectSancionador(search){
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
                    me.arraySancionador = respuesta.personas.data
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCadete(val1){
                let me = this;
                me.cadete = val1;
            },
            getSancion(val1){
                let me = this;
                me.esExtraordinario = false;
                me.sancion = val1;
                if (me.sancion) {
                    if (me.sancion.categoria === 'Extraordinario' && me.sancion.puntaje_dia !== 0) {
                        me.esExtraordinario = true;
                    }
                }
            },
            getDatosSancionador(val1){
                let me = this;
                me.sancionador = val1;
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarDemerito(page,buscar,criterio);
            },
            registrarDemerito(){
                if (this.validarDemerito()){
                    return;
                }

                let me = this;

                axios({
                    url: '/api/demeritos',
                    method: 'POST',
                    params: {
                        'sancionId': this.sancion.id,
                        'cadeteId': this.cadete.id,
                        'sancionadorId': this.sancionador.id,
                        'num_orden': this.num_orden,
                        'cant_dia': this.cant_dia
                    }
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarDemerito(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarDemerito(){
                if (this.validarDemerito()){
                    return;
                }

                let me = this;
                if (this.sancion.puntaje) {
                    this.cant_dia = 0;
                }

                axios({
                    url: '/api/demeritos/'+this.demerito_id,
                    method: 'PUT',
                    params: {
                        'sancionId': this.sancion.id,
                        'cadeteId': this.cadete.id,
                        'sancionadorId': this.sancionador.id,
                        'num_orden': this.num_orden,
                        'cant_dia': this.cant_dia,
                        'id': this.demerito_id
                    }
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarDemerito(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            eliminarDemerito(id){
                swal({
                    title: 'Esta seguro de Eliminar esta Demerito?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me = this;

                        axios({
                            url: '/api/demeritos/'+id,
                            method: 'DELETE',
                        }).then(function (response) {
                            me.listarDemerito(1,'','nombre');
                            swal(
                                'Eliminado!',
                                'El registro ha sido eliminado con éxito.',
                                'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });


                    } else if (
                        // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                })
            },
            validarDemerito(){
                this.errorDemerito=0;
                this.errorMostrarMsjDemerito =[];

                if (typeof this.sancion.id === 'undefined') this.errorMostrarMsjDemerito.push("Seleccione una sancion.");
                if (typeof this.cadete.id === 'undefined') this.errorMostrarMsjDemerito.push("Seleccione un cadete.");
                if (typeof this.sancionador.id === 'undefined') this.errorMostrarMsjDemerito.push("Seleccione un sancionador.");

                if (this.errorMostrarMsjDemerito.length) this.errorDemerito = 1;

                return this.errorDemerito;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.cadete= {};
                this.sancion= {};
                this.sancionador= {};
                this.num_orden = '';
                this.errorDemerito=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "demerito":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Demerito';
                                this.num_orden= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Demerito';
                                this.tipoAccion=2;
                                this.demerito_id=data['id'];
                                this.sancion=data['sancion'];
                                this.cadete=data['cadete'];
                                this.sancionador=data['sancionador'];
                                this.num_orden = data['num_orden'];
                                this.cant_dia = data['cant_dia'];
                                break;
                            }
                        }
                    }
                }
                this.selectCadete();
                this.selectSancion();
                this.selectSancionador();
            }
        },
        mounted() {
            this.listarDemerito(1,this.buscar,this.criterio);
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
