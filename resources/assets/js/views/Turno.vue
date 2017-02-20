<template>
	<div class="row">
		<turno-modal
            :turno="turno"
            :failure="failure"
            :errors="errors"
            :show="show"
            @hide="hide"
            @store="storeTurno"
            >        
        </turno-modal>
        <div class="content column is-12">
            <div class="title is-2">Turnos</div>
            <div class="nav menu">
                <div class="container">
                    <div class="nav-left">
                        <a id="add2" class="nav-item is-tab is-boxed" @click="open" ref="new">
                            <span class="icon-btn">
                                <i class="fa fa-plus"></i>
                            </span>
                        </a>
                        <ui-tooltip trigger="new" position="top center">Nuevo</ui-tooltip>
                        <slot></slot>                        
                    </div>                
                </div>
            </div>
        </div>
        <div class="row">
	        <pagination 
	            :pagination="pagination"
	            :offset="offset"
	            @change-page="changePage"></pagination>
	        <div class="table-responsive">        
	            <table class="table is-bordered is-striped">
		            <thead>
		                <tr>
		                    <th>Fecha</th>
		                    <th>Total Registros</th>
		                    <th colspan="3">Enlaces</th>
		                </tr>
		            </thead>
		            <tbody>
			            <tr is="turno-row"
		                	v-for="turno in turnos"
		                	:turno="turno"
		                	:failure="failure"
		                	:errors="errors"
		                	@edit="updateTurno"
		                	@remove="removeTurno">
		                </tr>
		            </tbody>
		        </table>
	        </div>
	        <pagination 
	            :pagination="pagination"
	            :offset="offset"
	            @change-page="changePage"></pagination>
        </div>
    </div>
</template>

<script>
import Pagination from '../components/Pagination'
import TurnoRow from '../components/TurnoRow'
import TurnoModal from '../components/TurnoModal'

export default {
	components: {
		Pagination, TurnoRow, TurnoModal
	},
	data () {
		return {
			failure: false,
			show: false,
			turno: {
				fecha: moment()._d
			},
			turnos: [],
			errors: [],
			pagination: {
                total: 0, per_page: 10,
                from: 1, to: 0,
                current_page: 1
            },
            offset: 4,
		}
	},
	mounted () {
		this.fetchTurnos(this.pagination.current_page)
	},
	methods: {
		fetchTurnos (page) {
			this.turnos = []
            axios.get('api/turno', {
                params: {
                    page: page,
                    limit: this.pagination.per_page
                }
            })
            .then( response => {
                this.turnos = response.data.data.map(turno => {
                    turno.editing = false
                    return turno
                })
                this.pagination = response.data
            })
		},
		storeTurno (turno) {
			axios.post('api/turno', turno)
            .then(response => {                    
                this.turno = response.data.turno
                this.turno.fecha = moment(this.turno.fecha)._d
                this.$message({
                    type: 'success',
                    message: 'Registro agregado correctamente'
                });
                this.show = false
                this.errors = {}
                this.failure = false
                this.$router.push('/turnos/' + this.turno.id + '/registros')
            }).catch( error => {
                this.failure = true
                this.errors = error.response.data.errors
                this.$message({
                    message: 'Ocurrió un error',
                    type: 'error'
                });
            })
		},
		updateTurno (turno) {
			axios.patch('api/turno/'+ turno.id, turno)
        	.then(response => {
        		turno.editing = false
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
		removeTurno (turno) {
			this.$confirm('Esto borrará permanentemente el registro. Desea continuar?', 'Advertencia', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancelar',
                type: 'warning'
            }).then(() => {
                let index = this.turnos.indexOf(turno)
                this.turnos.splice(index, 1)
                axios.delete('api/turno/'+ turno.id)
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
		open () {
			this.show = true
		},
		hide () {
            this.turno = {
				fecha: moment()._d
			}
            this.show = false
        },
		changePage (page) {
			this.fetchTurnos(page)
		}
	}
}
</script>