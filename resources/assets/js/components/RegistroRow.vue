<template>
	<transition 
		name="fade"
		enter-active-class="fadeInLeft"
		leave-active-class="fadeOutRight">
		<tr>
			<td v-if="registro.turno">{{ registro.turno.fecha }}</td>
			<td>{{ registro.caso }}</td>
			<td>{{ registro.fecha | format }}</td>
			<td><p v-for="denunciante in registro.denunciantes">{{ denunciante.nombre }}</p></td>
			<td><p v-for="denunciado in registro.denunciados">{{ denunciado.nombre }}</p></td>
			<td><p v-for="delito in registro.delitos">{{ delito.nombre }}</p></td>
			<td><p v-for="estado in registro.estados">{{ estado.nombre }}</p></td>
			<td>{{ registro.situacion_procesal | place }}</td>
			<td>{{ registro.observaciones }}</td>
			<td class="is-icon">
			  	<a @click="edit()" ref="edit">
			  	  	<i class="fa fa-edit"></i>
			  	</a>
			  	<ui-tooltip trigger="edit" position="top center">Editar</ui-tooltip>
			</td>
			<td class="is-icon">
	            <a @click="remove()" ref="delete">
				  	<i class="fa fa-trash"></i>
				</a>
				<ui-tooltip trigger="delete" position="top center">Eliminar</ui-tooltip>
			</td>		
		</tr>
	</transition>
</template>
<script>
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

			format(fecha){
				return moment(fecha).format('DD-MM-YYYY')
			}
		},
		methods: {			
			edit() {
				this.draft = JSON.parse(JSON.stringify(this.registro))
				this.registro.editing = true
				this.registro.fecha = moment(this.registro.fecha)._d
				this.$emit('edit', this.draft)
			},
			remove() {
				this.$emit('remove', this.registro)
			}
		}
	}
</script>