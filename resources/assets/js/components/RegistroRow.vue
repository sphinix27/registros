<template>
	<transition 
		name="fade"
		enter-active-class="fadeInLeft"
		leave-active-class="fadeOutRight">
	<tr>
		<template v-if="!registro.editing">
			<td>{{ registro.caso }}</td>
			<td>{{ registro.fecha | date }}</td>
			<td><p v-for="denunciante in registro.denunciantes">{{ denunciante.nombre }}</p></td>
			<td><p v-for="denunciado in registro.denunciados">{{ denunciado.nombre }}</p></td>
			<td><p v-for="delito in registro.delitos">{{ delito.nombre }}</p></td>
			<td><p v-for="estado in registro.estados">{{ estado.nombre }}</p></td>
			<td>{{ registro.situacion_procesal | place }}</td>
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
					help="Fecha del turno"
                	placeholder="Selecciona la fecha"
                	:custom-formatter="pickerFormatter"
                	:invalid="failure"
                	v-model="registro.fecha"
            		><p v-for="error in errors.fecha">{{ error }}</p></ui-datepicker>
            </td>
            <td>
				<div v-for="denunciante in registro.denunciantes">
					<ui-autocomplete
			               help="Nombre completo del denunciante."
			               name="denunciante"
			               :keys="{ label: 'nombre', value: 'nombre' }"
			               :min-chars="0"
			               :suggestions="personas"                
			               v-model="denunciante.nombre"
			               :invalid="failure"
			               @blur="idPersona(denunciante)"
			               @select="idPersona(denunciante)"
			               ><p v-for="error in errors.denunciante">{{ error }}</p>
			           </ui-autocomplete>
				</div>
				<ui-icon-button icon="add" size="normal" tooltip="Agregar denunciante" tooltip-position="top center" @click="add(1)"></ui-icon-button>
			</td>
            <td>
				<div v-for="denunciado in registro.denunciados">
					<ui-autocomplete
			            help="Nombre completo del denunciado."
			            name="denunciado"
			            :keys="{ label: 'nombre', value: 'nombre' }"
			            :min-chars="0"
			            :suggestions="personas"                
			            v-model="denunciado.nombre"
			            :invalid="failure"
			            @blur="idPersona(denunciado)"
			            @select="idPersona(denunciado)"
			            ><p v-for="error in errors.denunciado">{{ error }}</p>
			        </ui-autocomplete>
				</div>
				<ui-icon-button icon="add" size="normal" tooltip="Agregar denunciado" tooltip-position="top center" @click="add(2)"></ui-icon-button>
			</td>
			<td>
				<ui-select
					name="delitos"
					help="Uno o más delitos"
					has-search
					multiple
	                placeholder="Selecciona el/los delitos"
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
					name="estados"
					vertical
					multiple
	                placeholder="Selecciona el/los estados"
	                :keys="{ label: 'nombre', value: 'id' }"
	                :options="estados"
	                :invalid="failure"
	                v-model="registro.estados"
	            >
	            	<p v-for="error in errors.estados">{{ error }}</p>
	            </ui-select>
			</td>
			<td>
				<ui-radio-group
					vertical
					name="situacion"
	                placeholder="Selecciona la situacion"
	                :keys="{ label: 'label', value: 'value' }"
	                :options="situacion"
	                :invalid="failure"
	                v-model="registro.situacion_procesal"
	            >
	            	<p v-for="error in errors.situacion_procesal">{{ error }}</p>
	            </ui-radio-group
	            	vertical>
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
		props: ['delitos', 'estados', 'personas', 'registro', 'failure', 'errors'],

		data() {
			return {
				draft: {},
				situacion: [
            		{
            			label: 'Aprendido para Cautelar',
            			value: 'APR'
            		},
            		{
            			label: 'A disposición del Juez',
            			value: 'DIS'
            		},
            		{
            			label: 'Libre',
            			value: 'LIB'
            		}
            	]
			}
		},

		filters: {
			place(situacion){
				return (situacion === 'APR') ? 'Aprendido para cautelar' : (situacion === 'DIS') ? 'A disposición del Juez' : 'Libre'
			},

			date(fecha){
				return moment(fecha).format('DD-MM-YYYY')
			}
		},

		computed: {
			fecha() {
				return new Date(this.registro.fecha);
			}
		},

		methods: {
			idPersona(persona) {
				let person = this.personas.filter((obj) => {
					return obj.nombre === persona.nombre
				})
				_.isEmpty(person) ? persona.id = null : persona.id = person[0].id

			},
			add(option) {
				let persona = { id: '', nombre: ''}
				option == 1 ? this.registro.denunciantes.push(persona) : this.registro.denunciados.push(persona)
			},
			store() {
				this.$emit('store', this.registro)
			},
			edit() {
				this.draft = JSON.parse(JSON.stringify(this.registro))
				this.registro.editing = true
				this.registro.fecha = new Date(this.registro.fecha)
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
			},
			pickerFormatter(date) {
	            return date.toLocaleDateString();
	        },
		}
	}
</script>