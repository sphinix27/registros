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
            @edit="updateRegistro"
            >        
        </registro-modal-two>      
        <div class="content column is-12">
            <div class="title is-2">Registros</div>
            <div class="nav menu">
                <div class="container">
                    <div class="nav-left">
                        <slot></slot>                        
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
                        <th>Turno</th>
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
                        :reg=true
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
            RegistroRow, Pagination, RegistroModalTwo
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
            this.fetchRegistros(this.pagination.current_page)
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
                axios.get('api/registro', {
                    params: {
                        page: page,
                        limit: this.pagination.per_page
                    }
                })
                .then( response => {
                    this.delitos = response.data.delitos
                    this.estados = response.data.estados
                    this.personas = response.data.personas
                    this.registros = response.data.registros.data
                    this.pagination = response.data.registros
                })
            },
            updateRegistro(registro){
                axios.patch('api/registro/'+ registro.id, registro)
                .then(response => {
                    this.$message({
                        type: 'success',
                        message: 'Registro actualizado correctamente'
                    });
                    let reg = _.find(this.registros, (o) => {
                        return o.id === registro.id
                    })
                    let index = this.registros.indexOf(reg)
                    let rec = response.data.registro
                    this.registros.splice(index, 1, rec)
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
                this.errors = {}
                this.failure = false
            },
            changePage(page) {
                this.fetchRegistros(page)
            }
        }
    }
</script>
