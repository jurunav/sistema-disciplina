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
                        <i class="fa fa-align-justify"></i> Premios
                        <button type="button" @click="abrirModal('premio','registrar')" class="btn btn-secondary">
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
                                    <input type="text" v-model="buscar" @keyup.enter="listarPremio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPremio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Puntaje</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="premio in arrayPremio" :key="premio.id">
                                    <td>
                                        <button type="button" @click="abrirModal('premio','actualizar',premio)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="premio.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarPremio(premio.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarPremio(premio.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="premio.nombre"></td>
                                    <td v-text="premio.nombre_categoria"></td>
                                    <td v-text="premio.puntaje"></td>
                                    <td>
                                        <div v-if="premio.condicion">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idcategoria">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                        </select>                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del premio">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Puntaje</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="puntaje" class="form-control" placeholder="">                                        
                                    </div>
                                </div>

                                <div v-show="errorPremio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPremio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPremio()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPremio()">Actualizar</button>
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
                premio_id: 0,
                idcategoria : 0,
                nombre_categoria : '',
                nombre : '',
                puntaje : 0,
                arrayPremio : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPremio : 0,
                errorMostrarMsjPremio : [],
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
                arrayCategoria :[]
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
            listarPremio (page,buscar,criterio){
                let me=this;
                var url= '/premio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPremio = respuesta.premios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCategoria(){
                let me=this;
                var url= '/categoria/selectCategoria';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayCategoria = respuesta.categorias;
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
                me.listarPremio(page,buscar,criterio);
            },
            registrarPremio(){
                if (this.validarPremio()){
                    return;
                }
                
                let me = this;

                axios.post('/premio/registrar',{
                    'idcategoria': this.idcategoria,
                    'nombre': this.nombre,
                    'puntaje': this.puntaje,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPremio(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarPremio(){
               if (this.validarPremio()){
                    return;
                }
                
                let me = this;

                axios.put('/premio/actualizar',{
                    'idcategoria': this.idcategoria,
                    'nombre': this.nombre,
                    'puntaje': this.puntaje,
                    'id': this.premio_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPremio(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarPremio(id){
               swal({
                title: 'Esta seguro de desactivar este premio?',
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

                    axios.put('/premio/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPremio(1,'','nombre');
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
            activarPremio(id){
               swal({
                title: 'Esta seguro de activar este premio?',
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

                    axios.put('/premio/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPremio(1,'','nombre');
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
            validarPremio(){
                this.errorPremio=0;
                this.errorMostrarMsjPremio =[];

                if (this.idcategoria==0) this.errorMostrarMsjPremio.push("Seleccione una categoría.");
                if (!this.nombre) this.errorMostrarMsjPremio.push("El nombre del premio no puede estar vacío.");
                if (!this.puntaje) this.errorMostrarMsjPremio.push("El puntaje del premio debe ser un número y no puede estar vacío.");
               
                if (this.errorMostrarMsjPremio.length) this.errorPremio = 1;

                return this.errorPremio;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.idcategoria= 0;
                this.nombre_categoria = '';
                this.nombre = '';
                this.puntaje = 0;
		        this.errorPremio=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "premio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Premio';
                                this.idcategoria=0;
                                this.nombre_categoria='';
                                this.nombre= '';
                                this.puntaje=0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Premio';
                                this.tipoAccion=2;
                                this.premio_id=data['id'];
                                this.idcategoria=data['idcategoria'];
                                this.nombre = data['nombre'];
                                this.puntaje=data['puntaje'];
                                break;
                            }
                        }
                    }
                }
                this.selectCategoria();
            }
        },
        mounted() {
            this.listarPremio(1,this.buscar,this.criterio);
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
