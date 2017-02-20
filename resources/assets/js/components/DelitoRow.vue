<template>
	<transition 
		name="fade"
		enter-active-class="fadeInLeft"
		leave-active-class="fadeOutRight">
	<tr>
		<template v-if="!delito.editing">
			<td>{{ delito.articulo }}</td>
			<td>{{ delito.inciso }}</td>
			<td>{{ delito.nombre }}</td>
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
				<ui-textbox
					name="articulo"
					help="Solo números"
					placeholder="Introduce el artículo"
					:invalid="failure"
					v-model="delito.articulo">
					<p v-for="error in errors.articulo">{{ error }}</p>
				</ui-textbox>
			</td>
			<td>
				<ui-textbox
					name="inciso"
					help="Opcional"
					placeholder="Introduce el inciso"
					:invalid="failure"
					v-model="delito.inciso">
						<p v-for="error in errors.inciso">{{ error }}</p>		
				</ui-textbox>
			</td>
			<td>
				<ui-textbox
					name="nombre"
					help="Hasta 256 caracteres"
					placeholder="Introduce el nombre/detalle del delito"
					:invalid="failure"
					v-model="delito.nombre">
						<p v-for="error in errors.nombre">{{ error }}</p>
				</ui-textbox>
			</td>
			<template v-if="delito.id">
		    	<td class="is-icon">
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
			<template v-else>
				<td class="is-icon">
					<a @click="store()" id="store" ref="store">
				  	  	<i class="fa fa-check"></i>
				  	</a>
				  	<ui-tooltip trigger="store" position="top center">Crear</ui-tooltip>
				</td>
				<td class="is-icon">
				  	<a @click="cancel()" ref="cancel">
				  	  	<i class="fa fa-ban"></i>
				  	</a>
				  	<ui-tooltip trigger="cancel" position="top center">Cancelar</ui-tooltip>
				</td>			                    	
			</template>
		</template>
	</tr>
	</transition>
</template>
<script>
	export default {
		props: ['delito', 'failure', 'errors'],

		data() {
			return {
				draft: {},
			}
		},

		methods: {
			store() {
				this.$emit('store', this.delito)
			},
			edit() {
				this.draft = JSON.parse(JSON.stringify(this.delito))
				this.delito.editing = true				
			},
			update() {
				this.$emit('edit', this.delito)
			},
			remove() {
				this.$emit('remove', this.delito)
			},
			cancel() {
				this.delito.editing = false
				this.$emit('cancel', this.delito, this.draft)
			}
		}
	}
</script>