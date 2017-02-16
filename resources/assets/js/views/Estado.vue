<template>
	<div class="row">
		<div class="content column is-12">
            <div class="title is-2">Estados</div>
            <div class="nav menu">
                <div class="container">
                    <div class="nav-left">
                        <a id="add" class="nav-item is-tab" @click="createEstado()">
                            <span class="icon-btn">
                                <i class="fa fa-plus"></i>
                            </span>
                        </a>
                        <slot></slot>                        
                    </div>                
                </div>
            </div>
        </div>
        <table class="table is-bordered is-striped">
            <thead>
                <tr>
                    <th><abbr title="Nombre">Nombre</abbr></th>
                    <th colspan="2">Enlaces</th>
                </tr>
            </thead>
            <tbody>
            	<tr is="estado-row"
                	v-for="estado in estados"
                	:estado="estado"
                	:failure="failure"
                	:errors="errors"
                	@store="storeEstado"
                	@edit="updateEstado"
                	@remove="removeEstado"
                	@cancel="cancelEstado">
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import EstadoRow from '../components/EstadoRow'
    export default {
    	components: {
    		EstadoRow
    	},
        data() {
			return {
				failure: false,
				estados: [],
				errors: [],
			}
		},
		mounted() {
            axios.get('api/estado')
            .then( response => {
				this.estados = response.data.map(estado => {
					estado.editing = false
					return estado
				})
			})
        },
        methods: {
        	createEstado(){
        		let newEstado = {
        			articulo: '',
        			inciso: '',
        			nombre: '',
        			editing: true
        		}
        		this.estados.push(newEstado)
        	},
        	storeEstado(estado){
        		axios.post('api/estado', estado)
        		.then(response => {
        			let index = this.estados.indexOf(estado)
        			response.data.estado.editing = false
	                this.estados.splice(index, 1, response.data.estado)
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
        	updateEstado(estado){
        		axios.patch('api/estado/'+ estado.id, estado)
        		.then(response => {
        			estado.editing = false
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
        	removeEstado(estado){
        		this.$confirm('Esto borrará permanentemente el registro. Desea continuar?', 'Advertencia', {
          			confirmButtonText: 'OK',
          			cancelButtonText: 'Cancelar',
          			type: 'warning'
        		}).then(() => {
	        		let index = this.estados.indexOf(estado)
	                this.estados.splice(index, 1)
	        		axios.delete('api/estado/'+ estado.id)
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
        	cancelEstado(estado, draft){
        		this.$message({
			        type: 'info',
			        message: 'Cancelado'
          		});
        		let index = this.estados.indexOf(estado)
        		_.isEmpty(draft) ? this.estados.splice(index, 1) : this.estados.splice(index, 1, draft)
        		this.errors = {}
        		this.failure = false
        	},
        }
    }
</script>