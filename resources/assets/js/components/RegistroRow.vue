<template>
	<transition 
		name="fade"
		enter-active-class="fadeInLeft"
		leave-active-class="fadeOutRight">
	<tr>
		<template v-if="!registro.editing">
			<td>{{ registro.caso }}</td>
			<td>{{ registro.fecha }}</td>
			<td>{{ registro.denunciante }}</td>
			<td>{{ registro.denunciado }}</td>
			<td><p v-for="delito in registro.delitos">{{ delito.nombre }}</p></td>
			<td>{{ registro.estado }}</td>
			<td>{{ registro.situacion_procesal }}</td>
			<td>{{ registro.observaciones }}</td>
			<td class="is-icon">
			  	<a @click="edit()">
			  	  	<i class="fa fa-edit"></i>
			  	</a>
			</td>
			<td class="is-icon">
	            <a @click="remove()">
				  	<i class="fa fa-trash"></i>
				</a>			
			</td>			
		</template>
		<template v-else>
			<td>
				<ui-textbox
					name="caso"
					help="Solo números"
					placeholder="Introduce el N° de Caso"
					:invalid="failure"
					v-model="registro.caso">
					<p v-for="error in errors.caso">{{ error }}</p>
				</ui-textbox>
			</td>
			<td>
				<ui-datepicker
					name="caso"
					help="Solo números"
                	placeholder="Selecciona la fecha"
                	:invalid="failure"
                	v-model="fecha"
            		><p v-for="error in errors.fecha">{{ error }}</p></ui-datepicker>
            </td>
            <td>
				<ui-textbox
					name="denunciante"
					help="Nombre completo"
					placeholder="Introduce el Nombre del Denunciante"
					:invalid="failure"
					v-model="registro.denunciante">
					<p v-for="error in errors.denunciante">{{ error }}</p>
				</ui-textbox>
			</td>
            <td>
				<ui-textbox
					name="denunciado"
					help="Nombre completo"
					placeholder="Introduce el Nombre del Denunciado"
					:invalid="failure"
					v-model="registro.denunciado">
					<p v-for="error in errors.denunciado">{{ error }}</p>
				</ui-textbox>
			</td>
			<td>
				<ui-select
					name="delitos"
					help="Uno o más delitos"
					has-search
					multiple
	                placeholder="Selecciona los delitos"
	                :keys="{ label: 'nombre', value: 'id' }"
	                :options="delitos"
	                :invalid="failure"
	                v-model="registro.delitos"
	            >
	            	<p v-for="error in errors.delitos">{{ error }}</p>
	            </ui-select>
			</td>
			<td>
				<ui-select
					name="estado"
	                placeholder="Selecciona el estado"
	                :options="estados"
	                :invalid="failure"
	                v-model="registro.estado"
	            >
	            	<p v-for="error in errors.estado">{{ error }}</p>
	            </ui-select>
			</td>
			<td>
				<ui-select
					name="situacion"
	                placeholder="Selecciona la situacion"
	                :options="situacion"
	                :invalid="failure"
	                v-model="registro.situacion_procesal"
	            >
	            	<p v-for="error in errors.situacion_procesal">{{ error }}</p>
	            </ui-select>
			</td>
            <td>
				<ui-textbox
					name="observaciones"
					help="Hasta 256 caracteres"
					multiLine
					maxlenght="256"
					placeholder="Observaciones"
					:invalid="failure"
					v-model="registro.observaciones">
					<p v-for="error in errors.observaciones">{{ error }}</p>
				</ui-textbox>
			</td>			
			<template v-if="registro.id">
		    	<td class="is-icon">
				  	<a @click="update()">
				  	  	<i class="fa fa-check"></i>
				  	</a>
				</td>
				<td class="is-icon">
				  	<a @click="cancel()">
				  	  	<i class="fa fa-ban"></i>
				  	</a>
				</td>			                    	
			</template>
			<template v-else>
				<td class="is-icon">
					<a @click="store()" id="store">
				  	  	<i class="fa fa-check"></i>
				  	</a>
				</td>
				<td class="is-icon">
				  	<a @click="cancel()">
				  	  	<i class="fa fa-ban"></i>
				  	</a>
				</td>			                    	
			</template>
		</template>
	</tr>
	</transition>
</template>
<script>
import moment from 'moment'
	export default {
		props: ['delitos', 'registro', 'failure', 'errors'],

		data() {
			return {
				draft: {},
				estados: [
					'CAUTELAR',
					'IMPUTADO',
					'INICIADO',
					'OBSERVADO',
					'DESESTIMADO'
				],
				situacion: [
					'APRENDIDO PARA CAULTER', 'DISPOSICION DEL JUEZ', 'LIBRE'
				]
			}
		},

		computed: {
			fecha() {
				return new Date(this.registro.fecha);
			}
		},

		methods: {
			store() {
				this.$emit('store', this.registro)
			},
			edit() {
				this.draft = JSON.parse(JSON.stringify(this.registro))
				this.registro.editing = true				
			},
			update() {
				this.$emit('edit', this.registro)
			},
			remove() {
				this.$emit('remove', this.registro)
			},
			cancel() {
				this.registro.editing = false
				this.$emit('cancel', this.registro, this.draft)
			}
		}
	}
</script>