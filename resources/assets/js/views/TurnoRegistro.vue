<template>
    <div class="row">
    <registro-modal-two
            :delitos="delitos"
            :estados="estados"
            :personas="personas"
            :registro="record"
            :failure="failure"
            :errors="errors"
            :show="show"
            @hide="hide"
            @store="storeRegistro"
            @edit="updateRegistro"
            >        
        </registro-modal-two>      
        <div class="content column is-12">
            <div class="title is-2">Registro del Turno en Fecha: {{ turn.fecha }}</div>
            <div class="nav menu">
                <div class="container">
                    <div class="nav-left">
                        <a id="add2" class="nav-item is-tab is-active" @click="open" ref="new">
                            <span class="icon-btn">
                                <i class="fa fa-plus"></i>
                            </span>
                        </a>
                        <ui-tooltip trigger="new" position="top center">Nuevo</ui-tooltip>
                        <a class="nav-item is-tab is-active" :href="url" ref="inf">
                            <span class="icon-btn">
                                <i class="fa fa-file-pdf-o"></i>
                            </span>
                        </a>
                        <ui-tooltip trigger="inf" position="top center">Informe</ui-tooltip>
                        <slot></slot>
                    </div>
                    <div class="nav-right">
                        <router-link to="/turnos" class="nav-item is-tab">
                            <span class="icon-btn">
                                <i class="fa fa-undo"></i>
                            </span>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <pagination 
            :pagination="pagination"
            :offset="offset"
            @change-page="changePage"></pagination>
        <div class="table-responsive">
            <table class="table is-bordered is-striped">
                <thead>
                    <tr>
                        <th>Caso</th>
                        <th>Fecha</th>
                        <th>Denunciantes</th>
                        <th>Denunciados</th>
                        <th>Delitos</th>
                        <th>Estados</th>
                        <th>Situación Procesal</th>
                        <th>Observaciones</th>
                        <th colspan="2">Enlaces</th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="registro-row"
                        v-for="registro in registros"
                        :delitos="delitos"
                        :estados="estados"
                        :personas="personas"
                        :registro="registro"
                        :failure="failure"
                        :errors="errors"
                        @edit="edit"
                        @remove="removeRegistro">
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination 
            :pagination="pagination"
            :offset="offset"
            @change-page="changePage"></pagination>
    </div>
</template>

<script>
import RegistroModalTwo from '../components/RegistroModalTwo'
import RegistroRow from '../components/RegistroRow'
import Pagination from '../components/Pagination'
    export default {
        components: {
            Pagination, RegistroModalTwo, RegistroRow
        },
        data() {
            return {
                show: false,

                failure: false,
                pagination: {
                    total: 0, per_page: 10,
                    from: 1, to: 0,
                    current_page: 1
                },
                offset: 4,
                registros: [],
                errors: [],
                delitos: [],
                estados: [],
                personas: [],
                turn: [],
                turno: '',
                record: {
                    caso: '',
                    fecha: moment()._d,
                    denunciantes: [{ nombre:''}],
                    denunciados: [{ nombre:''}],
                    delitos: [],
                    estados: [],
                    situacion_procesal: '',
                    observaciones: ''
                }
            }
        },
        mounted() {
            this.turno = this.$route.params.id
            this.fetchRegistros(this.pagination.current_page)
        },
        computed: {
            url () {
                return 'pdf/'+this.turno
            }
        },
        methods: {
            hide () {
                this.record = {
                    caso: '',
                    fecha: moment()._d,
                    denunciados: [{ nombre:''}],
                    denunciantes: [{ nombre:''}],
                    delitos: [],
                    estados: [],
                    situacion_procesal: '',
                    observaciones: '',
                    editing: true
                }
                this.errors = [],
                this.failure = false
                this.show = false
            },
            open () {
                this.record.editing = false
                this.show = true
            },
            edit (registro) {
                this.record = registro
                this.record.editing = true
                this.record.fecha = moment(this.record.fecha)._d
                if(this.record.observaciones === null)
                    this.record.observaciones = ''
                this.show = true
            },
            fetchRegistros (page) {
                this.registros = []
                axios.get('api/registro/' + this.turno, {
                    params: {
                        page: page,
                        limit: this.pagination.per_page
                    }
                })
                .then( response => {
                    this.turn = response.data.turno
                    this.delitos = response.data.delitos
                    this.estados = response.data.estados
                    this.personas = response.data.personas
                    this.registros = response.data.registros.data
                    this.pagination = response.data.registros
                })
            },
            storeRegistro(registro){
                registro.turno_id = this.turno
                axios.post('api/registro', registro)
                .then(response => {                    
                    this.registros.push(response.data.registro)
                    this.$message({
                        type: 'success',
                        message: 'Registro agregado correctamente'
                    });
                    this.hide()
                }).catch( error => {
                    this.failure = true
                    this.errors = error.response.data.errors
                    this.$message({
                        message: 'Ocurrió un error',
                        type: 'error'
                    });
                })
            },
            updateRegistro(registro){
                axios.patch('api/registro/'+ registro.id, registro)
                .then(response => {
                    this.$message({
                        type: 'success',
                        message: 'Registro actualizado correctamente'
                    });
                    let index = this.registros.indexOf(registro)
                    this.registros.splice(index, 1, response.data.registro)
                    this.hide()
                }).catch( error => {
                    this.failure = true
                    this.errors = error.response.data.errors
                    this.$message({
                        message: 'Ocurrió un error',
                        type: 'error'
                    });
                })
            },
            removeRegistro(registro){
                this.$confirm('Esto borrará permanentemente el registro. Desea continuar?', 'Advertencia', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    let index = this.registros.indexOf(registro)
                    this.registros.splice(index, 1)
                    axios.delete('api/registro/'+ registro.id)
                    this.$message({
                        type: 'success',
                        message: 'Borrado completado'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Borrado cancelado'
                    });          
                });             
            },
            cancelRegistro(registro, draft){
                this.$message({
                    type: 'info',
                    message: 'Cancelado'
                });
                let index = this.registros.indexOf(registro)
                _.isEmpty(draft) ? this.registros.splice(index, 1) : this.registros.splice(index, 1, draft)
                this.hide()
            },
            changePage(page) {
                this.fetchRegistros(page)
            }
        }
    }
</script>
