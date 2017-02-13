<template>
	<div class="row">
		<div class="content column is-12">
            <div class="title is-2">Delitos</div>
            <div class="nav menu">
                <div class="container">
                    <div class="nav-left">
                        <a id="add" class="nav-item is-tab" @click="createDelito()">
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
                    <th><abbr title="Artículo">Artículo</abbr></th>
                    <th><abbr title="Inciso">Inciso</abbr></th>
                    <th><abbr title="Detalle">Detalle</abbr></th>
                    <th colspan="2">Enlaces</th>
                </tr>
            </thead>
            <tbody>
            	<tr is="delito-row"
                	v-for="delito in delitos"
                	:delito="delito"
                	:failure="failure"
                	:errors="errors"
                	@store="storeDelito"
                	@edit="updateDelito"
                	@remove="removeDelito"
                	@cancel="cancelDelito">
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import DelitoRow from '../components/DelitoRow'
    export default {
    	components: {
    		DelitoRow
    	},
        data() {
			return {
				failure: false,
				delitos: [],
				errors: [],
			}
		},
		mounted() {
            axios.get('delito')
            .then( response => {
				this.delitos = response.data.map(delito => {
					delito.editing = false
					return delito
				})
			})
        },
        methods: {
        	createDelito(){
        		let newDelito = {
        			articulo: '',
        			inciso: '',
        			nombre: '',
        			editing: true
        		}
        		this.delitos.push(newDelito)
        	},
        	storeDelito(delito){
        		axios.post('/delito', delito)
        		.then(response => {
        			let index = this.delitos.indexOf(delito)
        			response.data.delito.editing = false
	                this.delitos.splice(index, 1, response.data.delito)
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
        	updateDelito(delito){
        		axios.patch('/delito/'+ delito.id, delito)
        		.then(response => {
        			delito.editing = false
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
        	removeDelito(delito){
        		this.$confirm('Esto borrará permanentemente el registro. Desea continuar?', 'Advertencia', {
          			confirmButtonText: 'OK',
          			cancelButtonText: 'Cancelar',
          			type: 'warning'
        		}).then(() => {
	        		let index = this.delitos.indexOf(delito)
	                this.delitos.splice(index, 1)
	        		axios.delete('/delito/'+ delito.id)
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
        	cancelDelito(delito, draft){
        		this.$message({
			        type: 'info',
			        message: 'Cancelado'
          		});
        		let index = this.delitos.indexOf(delito)
        		_.isEmpty(draft) ? this.delitos.splice(index, 1) : this.delitos.splice(index, 1, draft)
        		this.errors = {}
        		this.failure = false
        	},
        }
    }
</script>