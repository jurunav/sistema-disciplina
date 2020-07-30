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
                    <i class="fa fa-align-justify"></i> Formulario Lista de Franco de Honor
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
                                            <label class="form-control-label">Salida</label>
                                            <select class="form-control" v-model="salida">
                                                <option value="franco_de_honor" selected>Franco de Honor (Sábado y domingo)</option>
                                                <option value="franco_domingo">Franco domingo</option>
                                                <option value="franco_medio_domingo">½ domingo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6" v-if="salida && salida === 'franco_de_honor'">
                                                <!--VALIDAR CUANDO SE CAMBIE EL SELECT DE SALIDA SI NO ES DE HONOR MOSTRAR SOLO DOMINGO-->
                                                <div class="form-group">
                                                    <label class="form-control-label">Sabado</label>
                                                    <input type="datetime-local" v-model="fecha_salida.sabado_inicio" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">hasta</label>
                                                    <input type="datetime-local" v-model="fecha_salida.sabado_fin" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Domingo</label>
                                                    <input type="datetime-local" v-model="fecha_salida.domingo_inicio" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">hasta</label>
                                                    <input type="datetime-local" v-model="fecha_salida.domingo_fin" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-control-label">Fecha Demeritos</label>
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
                                            <label class="form-control-label">Año Militar</label>
                                            <select class="form-control" v-model="year_ingreso">
                                                <option value="all" selected>Todos</option>
                                                <option value="1">Primer Año</option>
                                                <option value="2">Segundo Año</option>
                                                <option value="3">Tercer Año</option>
                                                <option value="4">Cuarto Año</option>
                                            </select>
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
                salida : 'franco_de_honor',
                fecha_salida : {
                    sabado_inicio : null,
                    sabado_fin : null,
                    domingo_inicio : null,
                    domingo_fin : null,
                },
                fecha_inicio : null,
                fecha_fin : null,
                year_ingreso : 'all',
                errorFormulario : 0,
                errorMsj : [],
            }
        },
        components: { },
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
                    salida: this.salida,
                    fechaSalida: this.fecha_salida,
                    startDate: this.fecha_inicio,
                    endDate: this.fecha_fin,
                    yearIngreso: this.year_ingreso
                };

                location.href = '/report/franco-de-honor?filters='+JSON.stringify(filters);
            },
            validarFormulario(){
                this.errorFormulario=0;
                this.errorMsj =[];

                if (this.salida === "franco_de_honor" && (this.fecha_salida.sabado_inicio === null && this.fecha_salida.sabado_fin === null))
                    this.errorMsj.push("Seleccione una fecha y hora de salida para dia Sabado");

                if (this.fecha_salida.domingo_inicio === null && this.fecha_salida.domingo_fin === null)
                    this.errorMsj.push("Seleccione una fecha y hora de salida para el dia Domingo");

                if (this.fecha_inicio === null && this.fecha_fin === null)
                    this.errorMsj.push("Seleccione una fecha inicio y una fecha fin de Demeritos");

                if (this.errorMsj.length) this.errorFormulario = 1;

                return this.errorFormulario;
            },
        },
        mounted() {
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
