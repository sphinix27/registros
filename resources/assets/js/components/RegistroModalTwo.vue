<template>
	<vodal :show="show" animation="door" @hide="hide" :width=700 :height=800>
		<div class="modal__header">
			<h4 class="title is-4">Registros</h4>
			<span class="vodal-close" @click="hide" />
		</div>
        <div class="box modal__body">
        	<p class="control">
	            <ui-textbox
	                floating-label
	                label="N° de caso FIS"
	                name="caso"
	                help="El número del caso FIS"
	                v-model="registro.caso"
	                :invalid="failure"><p v-for="error in errors.caso">{{ error }}</p>
	            </ui-textbox>
	        </p>
	        <p class="control">
	            <ui-datepicker
	                floating-label
	                label="Fecha"
	                name="fecha"
	                :custom-formatter="pickerFormatter"
	                orientation="landscape"
	                help="Fecha de registro del caso"
	                :invalid="failure"
	                v-model="registro.fecha"
	                ><p v-for="error in errors.fecha">{{ error }}</p>
	            </ui-datepicker>
	        </p>	
	        <p class="control">
		        <div class="panel">
					<div class="panel-block" v-for="denunciante in registro.denunciantes">
						<ui-autocomplete
			                help="Nombre completo del denunciante."
			                floating-label
			                label="Denunciante o Querrellante"
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
					<ui-icon-button icon="add" size="normal" tooltip="Agregar denunciante" tooltip-position="top center" @click="addDenunciante"></ui-icon-button>
				</div>
	        </p>	
	        <p class="control">
		        <div class="panel">
					<div class="panel-block" v-for="denunciado in registro.denunciados">
						<ui-autocomplete
			                help="Nombre completo del denunciado."
			                floating-label
			                label="Denunciante o Querrellante"
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
					<ui-icon-button icon="add" size="normal" tooltip="Agregar denunciado" tooltip-position="top center" @click="addDenunciado"></ui-icon-button>
				</div>
	        </p>
	        <p class="control">
	            <ui-select
	                floating-label
	                label="Delitos"
	                name="delitos"
	                help="Seleccione uno o más delitos"
	                has-search
	                multiple                
	                :options="delitos"
	                :invalid="failure"
	                v-model="registro.delitos"
	            >
	                <p v-for="error in errors.delitos">{{ error }}</p>
	            </ui-select>
	        </p>
	        <p class="control">
	            <ui-select
	            	lfloating-label
	                label="Estados"
	                name="estados"
	                help="Seleccione uno o más estados"
	                has-search
	                multiple                
	                :options="estados"
	                :invalid="failure"
	                v-model="registro.estados"
	            ><p v-for="error in errors.estados">{{ error }}</p>
	            </ui-select>
	        </p>
	        <p class="control">
	            <ui-radio-group
	            	label="Situación procesal del Autor Presunto"
	            	name="situacion"
	                vertical
	                help="Seleccione la situación procesal del autor"
	                :options="situacion"
	                v-model="registro.situacion_procesal"
	                :invalid="failure"
	            ><p v-for="error in errors.situacion_procesal">{{ error }}</p>
	            </ui-radio-group>
	        </p>
	        <p class="control">
	            <ui-textbox
	                floating-label
	                label="Observaciones"
	                name="observaciones"
	                help="Hasta 256 caracteres."  
	                :multi-line="true"
	                :maxlength="256"              
	                v-model="registro.observaciones"
	                :invalid="failure"><p v-for="error in errors.observaciones">{{ error }}</p>
	            </ui-textbox>
	        </p>
        </div>
        <div class="modal__footer">
        	<template v-if="registro.editing">
        		<ui-button color="primary" @click="edit">Guardar</ui-button>
        	</template>
        	<template v-else>
            	<ui-button color="primary" @click="store">Crear</ui-button>        		
        	</template>
            <ui-button slot="cancel" @click="hide">Cancelar</ui-button>
        </div>
    </vodal>
</template>

<script>
import Vodal from 'vodal'
	export default {
		props: ['delitos', 'estados', 'personas', 'registro', 'failure', 'errors', 'show'],
		components: { Vodal },
		data() {
            return {
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
		methods: {
			hide () {
				this.$emit('hide')
			},

			idPersona(persona) {
				let person = this.personas.filter((obj) => {
					return obj.nombre === persona.nombre
				})
				_.isEmpty(person) ? persona.id = null : persona.id = person[0].id

			},
			addDenunciante() {
				let denunciante = { id: '', nombre: ''}
				this.registro.denunciantes.push(denunciante)
			},
			addDenunciado() {
				let denunciado = { id:'', nombre: ''}
				this.registro.denunciados.push(denunciado)
			},
			store() {
				this.$emit('store', this.registro)
			},
			edit() {
				this.$emit('edit', this.registro)
			},
			pickerFormatter(date) {
            	return moment(date).format('DD-MM-YYYY')
        	},
    	},
	}
</script>

<style lang="scss">
@import "~vodal/door.css";
.modal__body {
	width: 100%;
	height: 86%;
	overflow-x: hidden;
    overflow-y: scroll;
}

.modal__header {
	align-items: center;
    background-color: #F5F5F5;
    box-shadow: 0 1px 1px rgba(black, 0.16);
    display: block;
    height: 7%;
    padding: 15px;
    position: relative;
    z-index: 0;
    border-bottom: 1px solid #eceeef;
}

.modal__footer {
	height: 7%;
	border-top: 1px solid #eceeef;
	padding: 5px;
}
</style>