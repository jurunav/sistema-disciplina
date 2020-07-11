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
                        <i class="fa fa-align-justify"></i> Sanciones
                        <button type="button" @click="abrirModal('sancion','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="puntaje">Puntaje</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarSancion(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarSancion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Puntaje</th>
                                    <th>Puntaje/dia</th>
                                    <th>Categoría</th>
                                    <th>Articulo</th>
                                    <th>Grupo</th>
                                    <th>Inciso</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sancion in arraySancion" :key="sancion.id">
                                    <td>
                                        <button type="button" @click="abrirModal('sancion','actualizar',sancion)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="sancion.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarSancion(sancion.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarSancion(sancion.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="sancion.nombre"></td>
                                    <td v-text="sancion.puntaje"></td>
                                    <td v-text="sancion.puntaje_dia"></td>
                                    <td v-text="sancion.categoria"></td>
                                    <td v-text="sancion.articulo"></td>
                                    <td v-text="sancion.grupo"></td>
                                    <td v-text="sancion.inciso"></td>
                                    <td>
                                        <div v-if="sancion.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la sanción">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Puntaje</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="puntaje" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Puntaje por día</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="puntaje_dia" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                                    <div class="col-md-9">
                                        <select v-model="categoria" class="form-control">
                                            <option value="Extraordinario">Extraordinario</option>
                                            <option value="Falta leve">Falta leve</option>
                                            <option value="Falta grave">Falta grave</option>
                                            <option value="Falta gravisima">Falta gravísima</option>
                                        </select>                                    
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Articulo</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="articulo" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Grupo</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="grupo" class="form-control" placeholder="Grupo de la sanción">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Inciso</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="inciso" class="form-control" placeholder="">                                        
                                    </div>
                                </div>

                                <div v-show="errorSancion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSancion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarSancion()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarSancion()">Actualizar</button>
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
    export default {
        data (){
            return {
                sancion_id: 0,
                nombre : '',
                puntaje : 0,
                puntaje_dia : 0,
                categoria : '',
                articulo : 0,
                grupo : '',
                inciso : 0,
                arraySancion : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorSancion : 0,
                errorMostrarMsjSancion : [],
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
            }
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
            listarSancion (page,buscar,criterio){
                let me=this;
                var url= '/sancion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySancion = respuesta.sanciones.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarSancion(page,buscar,criterio);
            },
            registrarSancion(){
                if (this.validarSancion()){
                    return;
                }
                
                let me = this;

                axios.post('/sancion/registrar',{
                    'nombre': this.nombre,
                    'puntaje': this.puntaje,
                    'puntaje_dia': this.puntaje_dia,
                    'categoria': this.categoria,
                    'articulo': this.articulo,
                    'grupo': this.grupo,
                    'inciso': this.inciso,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarSancion(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarSancion(){
               if (this.validarSancion()){
                    return;
                }
                
                let me = this;

                axios.put('/sancion/actualizar',{
                    'nombre': this.nombre,
                    'puntaje': this.puntaje,
                    'puntaje_dia': this.puntaje_dia,
                    'categoria': this.categoria,
                    'articulo': this.articulo,
                    'grupo': this.grupo,
                    'inciso': this.inciso,
                    'id': this.sancion_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarSancion(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarSancion(id){
               swal({
                title: 'Esta seguro de desactivar esta sanción?',
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

                    axios.put('/sancion/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarSancion(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
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
            activarSancion(id){
               swal({
                title: 'Esta seguro de activar esta sanción?',
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

                    axios.put('/sancion/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarSancion(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
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
            validarSancion(){
                this.errorSancion=0;
                this.errorMostrarMsjSancion =[];

                if (!this.nombre) this.errorMostrarMsjSancion.push("El nombre de la sanción no puede estar vacío.");
                if (!this.categoria) this.errorMostrarMsjSancion.push("La categoria de la sanción no puede estar vacío.");
               
                if (this.errorMostrarMsjSancion.length) this.errorSancion = 1;

                return this.errorSancion;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre = '';
                this.puntaje = 0;
                this.puntaje_dia = 0;
                this.categoria = '';
                this.articulo = 0;
                this.grupo = '';
                this.inciso = 0;
		        this.errorSancion=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "sancion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Sancion';
                                this.nombre= '';
                                this.puntaje=0;
                                this.puntaje_dia = 0;
                                this.categoria = '';
                                this.articulo = 0;
                                this.grupo = '';
                                this.inciso = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Sanción';
                                this.tipoAccion=2;
                                this.sancion_id=data['id'];
                                this.nombre = data['nombre'];
                                this.puntaje=data['puntaje'];
                                this.puntaje_dia=data['puntaje_dia'];
                                this.categoria=data['categoria'];                               
                                this.articulo=data['articulo'];
                                this.grupo=data['grupo'];
                                this.inciso=data['inciso'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarSancion(1,this.buscar,this.criterio);
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
