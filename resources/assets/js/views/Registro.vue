<template>
    <div class="row">
    <ui-modal ref="modal" title="Agregar Registro" size="large">
        <registro-modal
            :delitos="delitos"
            :estados="estados"
            :personas="personas"
            :registro="record"
            :failure="failure"
            :errors="errors"
            @store="storeRegistro2"
            @cancel="closeModal('modal')"
            >        
        </registro-modal>             
    </ui-modal>
        <div class="content column is-12">
            <div class="title is-2">Registros</div>
            <div class="nav menu">
                <div class="container">
                    <div class="nav-left">
                        <a id="add" class="nav-item is-tab" @click="createRegistro()">
                            <span class="icon-btn">
                                <i class="fa fa-plus"></i>
                            </span>
                        </a>
                        <a id="add2" class="nav-item is-tab" @click="openModal('modal')">
                            <span class="icon-btn">
                                <i class="fa fa-plus"></i>
                            </span>
                        </a>
                        <slot></slot>                        
                    </div>                
                </div>
            </div>
        </div>
        <div class="table-responsive">
        <table class="table is-bordered is-striped">
            <thead>
                <tr>
                    <th>N° DE CASO FIS</th>
                    <th>FECHA</th>
                    <th>DENUCIANTE O QUERRELANTE</th>
                    <th>DENUCIADO O QUERRELLADO</th>
                    <th>DELITO/S</th>
                    <th>ESTADO DEL CASO</th>
                    <th>SITUACION PROCESAL DE AUTOR PRESUNTO</th>
                    <th>OBSERVACIONES</th>
                    <th colspan="2">Enlaces</th>
                </tr>
            </thead>
            <tbody>
                <tr is="registro-row"
                    v-for="registro in registros" 
                    v-bind:key="registro"
                    :delitos="delitos"
                    :estados="estados"
                    :personas="personas"
                    :registro="registro"
                    :failure="failure"
                    :errors="errors"
                    @store="storeRegistro"
                    @edit="updateRegistro"
                    @remove="removeRegistro"
                    @cancel="cancelRegistro">
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</template>

<script>
import RegistroRow from '../components/RegistroRow'
import RegistroModal from '../components/RegistroModal'
import Vodal from 'vodal'
    export default {
        components: {
            RegistroRow, Vodal, RegistroModal
        },
        data() {
            return {
                failure: false,
                registros: [],
                errors: [],
                delitos: [],
                estados: [],
                personas: [],
                record: {
                    caso: '',
                    fecha: new Date(),
                    denunciantes: [{ nombre:''}],
                    denunciados: [{ nombre:''}],
                    delitos: [],
                    estados: [],
                    situacion_procesal: '',
                    observaciones: '',
                    editing: true
                }
            }
        },
        mounted() {
            axios.get('api/registro')
            .then( response => {
                this.delitos = response.data.delitos
                this.estados = response.data.estados
                this.personas = response.data.personas
                this.registros = response.data.registros.map(registro => {
                    registro.editing = false
                    return registro
                })
            })
        },
        methods: {
            openModal(ref) {
                this.$refs[ref].open()
            },
            closeModal(ref) {
                this.errors = []
                this.failure = false
                this.$refs[ref].close()
            },
            createRegistro(){
                let newRegistro = {
                    caso: '',
                    fecha: new Date(),
                    delitos: [],
                    estados: [],
                    situacion_procesal: '',
                    observaciones: '',
                    denunciados: [],
                    denunciantes: [],
                    editing: true
                }
                this.registros.push(newRegistro)
            },
            storeRegistro(registro){
                axios.post('api/registro', registro)
                .then(response => {
                    let index = this.registros.indexOf(registro)
                    response.data.registro.editing = false
                    this.registros.splice(index, 1, response.data.registro)
                    this.$message({
                        type: 'success',
                        message: 'Registro agregado correctamente'
                    });
                    this.errors = {}
                    this.failure = false
                }).catch( error => {
                    this.failure = true
                    this.errors = error.response.data.errors
                    this.$message({
                        message: 'Ocurrió un error',
                        type: 'error'
                    });
                })
            },
            storeRegistro2(registro){
                axios.post('api/registro', registro)
                .then(response => {                    
                    response.data.registro.editing = false
                    this.registros.push(response.data.registro)
                    this.$message({
                        type: 'success',
                        message: 'Registro agregado correctamente'
                    });
                    this.$refs['modal'].close()
                    this.record = {
                        caso: '',
                        fecha: new Date(),
                        denunciado: '',
                        denunciante: '',
                        delitos: [],
                        estados: [],
                        situacion_procesal: '',
                        observaciones: '',
                        editing: true
                    }
                    this.errors = {}
                    this.failure = false
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
                    registro.editing = false
                    this.$message({
                        type: 'success',
                        message: 'Registro actualizado correctamente'
                    });
                    this.errors = {}
                    this.failure = false
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
        }
    }
</script>

<style lang="scss">
@import "~vodal/door.css";

</style>