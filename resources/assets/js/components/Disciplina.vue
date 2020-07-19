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
                        <i class="fa fa-align-justify"></i> Disciplinas
                        <button type="button" @click="abrirModal('disciplina','registrar')" class="btn btn-secondary">
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
                                    <input type="text" v-model="buscar" @keyup.enter="listarDisciplina(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDisciplina(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Premio</th>
                                    <th>Categoría</th>
                                    <th>Puntaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="disciplina in arrayDisciplina" :key="disciplina.id">
                                    <td>
                                        <button type="button" @click="abrirModal('disciplina','actualizar',disciplina)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="disciplina.id">
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarDisciplina(disciplina.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td> {{disciplina.nombre }}</td>
                                    <td> {{ disciplina.premio.nombre }}</td>
                                    <td> {{ (disciplina.categoria !== null) ?disciplina.categoria.nombre :''}}</td>
                                    <td> {{disciplina.puntaje}}</td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categorías</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="categoria.id">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="option in arrayCategoria" :key="option.id" :value="option.id" v-text="option.nombre"></option>
                                        </select>                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Premios</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="premio.id">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="option in arrayPremio" :key="option.id" :value="option.id" v-text="option.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la disciplina">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Puntaje</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="puntaje" class="form-control" placeholder="">                                        
                                    </div>
                                </div>

                                <div v-show="errorDisciplina" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDisciplina" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDisciplina()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDisciplina()">Actualizar</button>
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
                disciplina_id: 0,
                categoria : {},
                premio :  {},
                nombre : '',
                puntaje : 0,
                arrayDisciplina : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDisciplina : 0,
                errorMostrarMsjDisciplina : [],
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
                arrayCategoria :[],
                arrayPremio :[]
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
            listarDisciplina (page,buscar,criterio){
                let me=this;
                console.log('listar');
                var data = {
                    page: page,
                    buscar: buscar,
                    criterio: criterio
                };

                axios({
                    url: '/api/disciplinas',
                    method: 'GET',
                    params: data
                }).then(function (response) {
                    var respuesta= response.data.results;
                    me.arrayDisciplina = respuesta.disciplinas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectPremio(){
                let me=this;
                axios({
                    url: '/api/premios',
                    method: 'GET',
                    params: {
                        limit: 200
                    }
                }).then(function (response) {
                    var respuesta= response.data.results;
                    me.arrayPremio = respuesta.premios.data;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            selectCategoria(){
                let me=this;
                axios({
                    url: '/api/categorias',
                    method: 'GET',
                    params: {
                        limit: 200
                    }
                }).then(function (response) {
                    var respuesta= response.data.results;
                    me.arrayCategoria = respuesta.categorias.data;
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
                me.listarDisciplina(page,buscar,criterio);
            },
            registrarDisciplina(){
                if (this.validarDisciplina()){
                    return;
                }
                
                let me = this;

                axios({
                    url: '/api/disciplinas',
                    method: 'POST',
                    params: {
                        'premio': this.premio.id,
                        'categoria': this.categoria.id,
                        'nombre': this.nombre,
                        'puntaje': this.puntaje,
                    }
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarDisciplina(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarDisciplina(){
               if (this.validarDisciplina()){
                    return;
                }
                
                let me = this;

                axios({
                    url: '/api/disciplinas/'+this.disciplina_id,
                    method: 'PUT',
                    params: {
                        'premio': this.premio.id,
                        'categoria': this.categoria.id,
                        'nombre': this.nombre,
                        'puntaje': this.puntaje,
                        'id': this.disciplina_id
                    }
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarDisciplina(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            eliminarDisciplina(id){
               swal({
                title: 'Esta seguro de Eliminar esta Disciplina?',
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
                        url: '/api/disciplinas/'+id,
                        method: 'DELETE',
                    }).then(function (response) {
                        me.listarDisciplina(1,'','nombre');
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
            validarDisciplina(){
                this.errorDisciplina=0;
                this.errorMostrarMsjDisciplina =[];

                if (this.premio === null) this.errorMostrarMsjDisciplina.push("Seleccione un premio.");
                if (!this.nombre) this.errorMostrarMsjDisciplina.push("El nombre de la disciplina no puede estar vacío.");
                if (!this.puntaje) this.errorMostrarMsjDisciplina.push("El puntaje de la disciplina debe ser un número y no puede estar vacío.");
               
                if (this.errorMostrarMsjDisciplina.length) this.errorDisciplina = 1;

                return this.errorDisciplina;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.categoria= {};
                this.premio= {};
                this.nombre = '';
                this.puntaje = 0;
		        this.errorDisciplina=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "disciplina":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Disciplina';
                                this.nombre= '';
                                this.puntaje=0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Disciplina';
                                this.tipoAccion=2;
                                this.disciplina_id=data['id'];
                                this.premio=data['premio'];
                                if (data['categoria'] !== null) {
                                    this.categoria=data['categoria'];
                                }
                                this.nombre = data['nombre'];
                                this.puntaje=data['puntaje'];
                                break;
                            }
                        }
                    }
                }
                this.selectCategoria();
                this.selectPremio();
            }
        },
        mounted() {
            this.listarDisciplina(1,this.buscar,this.criterio);
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
