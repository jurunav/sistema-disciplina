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
                        <i class="fa fa-align-justify"></i> Meritos
                        <button type="button" @click="abrirModal('merito','registrar')" class="btn btn-secondary">
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
                                    <input type="text" v-model="buscar" @keyup.enter="listarMerito(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarMerito(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Num Orden</th>
                                    <th>Disciplina</th>
                                    <th>Descripcion</th>
                                    <th>Cadete</th>
                                    <th>Creado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="merito in arrayMerito" :key="merito.id">
                                    <td>
                                        <button type="button" @click="abrirModal('merito','actualizar',merito)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="merito.id">
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarMerito(merito.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td> {{ merito.num_orden }}</td>
                                    <td> {{ merito.disciplina.nombre }}</td>
                                    <td> {{ merito.descripcion }}</td>
                                    <td> {{ merito.cadete.persona.nombre }}</td>
                                    <td> {{ merito.created_at }}</td>
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
                                                @input="getDatosCadete">
                                        </v-select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Disciplinas</label>
                                    <div class="col-md-9">
                                        <v-select
                                                @search="selectDisciplina"
                                                label="nombre"
                                                :options="arrayDisciplina"
                                                placeholder="Buscar Disciplina..."
                                                @input="getDatosDisciplina">
                                        </v-select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Num Orden</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="num_orden" class="form-control" placeholder="Numero de Orden">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="">
                                    </div>
                                </div>

                                <div v-show="errorMerito" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjMerito" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMerito()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMerito()">Actualizar</button>
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
                merito_id: 0,
                cadete : {},
                disciplina :  {},
                num_orden : '',
                descripcion : '',
                arrayMerito : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMerito : 0,
                errorMostrarMsjMerito : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                arrayCadete :[],
                arrayDisciplina :[]
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
            listarMerito (page,buscar,criterio){
                let me=this;
                console.log('listar');
                var data = {
                    page: page,
                    buscar: buscar,
                    criterio: criterio
                };

                axios({
                    url: '/api/meritos',
                    method: 'GET',
                    params: data
                }).then(function (response) {
                    var respuesta= response.data.results;
                    me.arrayMerito = respuesta.meritos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDisciplina(search, loading){
                let me=this;
                axios({
                    url: '/api/disciplinas',
                    method: 'GET',
                    params: {
                        limit: 10,
                        search:search
                    }
                }).then(function (response) {
                    let respuesta = response.data.results;
                    q: search;
                    me.arrayDisciplina = respuesta.disciplinas.data;
                    loading(false);
                }).catch(function (error) {
                        console.log(error);
                });
            },
            selectCadete(search, loading){
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
                    loading(false);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCadete(val1){
                console.log(val1);
                let me = this;
                me.loading = true;
                me.cadete.id = val1.id;
            },
            getDatosDisciplina(val1){
                let me = this;
                me.loading = true;
                me.disciplina.id = val1.id;
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarMerito(page,buscar,criterio);
            },
            registrarMerito(){
                if (this.validarMerito()){
                    return;
                }
                
                let me = this;

                axios({
                    url: '/api/meritos',
                    method: 'POST',
                    params: {
                        'disciplinaId': this.disciplina.id,
                        'cadeteId': this.cadete.id,
                        'num_orden': this.num_orden,
                        'descripcion': this.descripcion,
                    }
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarMerito(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarMerito(){
               if (this.validarMerito()){
                    return;
                }
                
                let me = this;

                axios({
                    url: '/api/meritos/'+this.merito_id,
                    method: 'PUT',
                    params: {
                        'disciplinaId': this.disciplina.id,
                        'cadeteId': this.cadete.id,
                        'num_orden': this.num_orden,
                        'descripcion': this.descripcion,
                        'id': this.merito_id
                    }
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarMerito(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            eliminarMerito(id){
               swal({
                title: 'Esta seguro de Eliminar esta Merito?',
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
                        url: '/api/meritos/'+id,
                        method: 'DELETE',
                    }).then(function (response) {
                        me.listarMerito(1,'','nombre');
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
            validarMerito(){
                this.errorMerito=0;
                this.errorMostrarMsjMerito =[];

                if (this.disciplina === null) this.errorMostrarMsjMerito.push("Seleccione un disciplina.");
                if (!this.num_orden) this.errorMostrarMsjMerito.push("El numero de orden no puede estar vacío.");
                if (!this.descripcion) this.errorMostrarMsjMerito.push("La descripcion de merito no puede estar vacío.");
               
                if (this.errorMostrarMsjMerito.length) this.errorMerito = 1;

                return this.errorMerito;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.cadete= {};
                this.disciplina= {};
                this.num_orden = '';
                this.descripcion = '';
		        this.errorMerito=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "merito":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Merito';
                                this.num_orden= '';
                                this.descripcion='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Merito';
                                this.tipoAccion=2;
                                this.merito_id=data['id'];
                                this.disciplina=data['disciplina'];
                                this.cadete=data['cadate'];
                                this.num_orden = data['num_orden'];
                                this.descripcion=data['descripcion'];
                                break;
                            }
                        }
                    }
                }
                this.selectCadete();
                this.selectDisciplina();
            }
        },
        mounted() {
            this.listarMerito(1,this.buscar,this.criterio);
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
