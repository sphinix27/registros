<template>
	<div>
        <div class="box">
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
	                :keys="{ label: 'nombre', value: 'id' }"
	                :options="delitos"
	                :invalid="failure"
	                v-model="registro.delitos"
	            >
	                <p v-for="error in errors.delitos">{{ error }}</p>
	            </ui-select>
	        </p>
	        <p class="control">
	            <ui-checkbox-group
	            	label="Estado del caso"
	            	name="estados"
	            	help="Seleccione el/los estados del caso"
	                :keys="{ label: 'nombre', value: 'id' }"
	                :options="estados"
	                v-model="registro.estados"
	                :invalid="failure"
	            ><p v-for="error in errors.estados">{{ error }}</p>
	            </ui-checkbox-group>
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
        <div slot="footer">
            <ui-button color="primary" @click="store">Crear</ui-button>
            <ui-button slot="cancel" @click="cancel">Cancelar</ui-button>
        </div>
    </div>
</template>

<script>
	export default {
		props: ['delitos', 'estados', 'personas', 'registro', 'failure', 'errors'],
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
			cancel() {
				this.$emit('cancel')
			},
			pickerFormatter(date) {
            	return date.toLocaleDateString();
        	},
    	},
	}
</script>