<template>
	<transition 
		name="fade"
		enter-active-class="fadeInLeft"
		leave-active-class="fadeOutRight">
	<tr>
		<template v-if="!turno.editing">
			<td>{{ turno.fecha | fecha }}</td>
			<td>{{ turno.total_registros }}</td>
			<td class="is-icon">
			  	<a @click="show()" ref="show">
			  	  	<i class="fa fa-list-ul"></i>
			  	</a>
			  	<ui-tooltip trigger="show" position="top center">Ver turno</ui-tooltip>
			</td>
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
		</template>
		<template v-else>
			<td>
				<ui-datepicker
	                floating-label
	                label="Fecha"
	                name="fecha"
	                :custom-formatter="pickerFormatter"
	                orientation="landscape"
	                help="Fecha del turno"                
	                :invalid="failure"
	                v-model="turno.fecha"
	                ><p v-for="error in errors.fecha">{{ error }}</p>
	            </ui-datepicker>
			</td>
			<td>{{ turno.total_registros }}</td>
		    <td class="is-icon" colspan="2">
			  	<a @click="update()" ref="update">
			  	  	<i class="fa fa-check"></i>
			  	</a>
			  	<ui-tooltip trigger="update" position="top center">Guardar</ui-tooltip>
			</td>
			<td class="is-icon">
			  	<a @click="cancel()" ref="cancel">
			  	  	<i class="fa fa-ban"></i>
			  	</a>
			  	<ui-tooltip trigger="cancel" position="top center">Cancelar</ui-tooltip>
			</td>
		</template>
	</tr>
	</transition>
</template>
<script>
	export default {
		props: ['turno', 'failure', 'errors'],

		data() {
			return {
				draft: {},
			}
		},
		filters: {
			fecha (date) {
				return moment(date).format("dddd, D [de] MMMM [de] YYYY")
			}
		},
		methods: {
			edit() {
				this.draft = JSON.parse(JSON.stringify(this.turno))
				this.turno.editing = true
				this.turno.fecha = moment(this.turno.fecha)._d
			},
			update() {
				this.$emit('edit', this.turno)
			},
			remove() {
				this.$emit('remove', this.turno)
			},
			cancel() {
				this.turno.editing = false
				this.$emit('cancel', this.turno, this.draft)
			},
			show () {
				this.$router.push('turnos/' + this.turno.id + '/registros')
			},			
			pickerFormatter(date) {
            	return moment(date).format('DD-MM-YYYY')
        	},
		}
	}
</script>