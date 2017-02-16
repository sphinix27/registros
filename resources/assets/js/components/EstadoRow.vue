<template>
	<transition 
		name="fade"
		enter-active-class="fadeInLeft"
		leave-active-class="fadeOutRight">
	<tr>
		<template v-if="!estado.editing">
			<td>{{ estado.nombre }}</td>
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
					name="nombre"
					help="Hasta 256 caracteres"
					placeholder="Introduce el nombre/detalle del estado"
					:invalid="failure"
					v-model="estado.nombre">
						<p v-for="error in errors.nombre">{{ error }}</p>
				</ui-textbox>
			</td>
			<template v-if="estado.id">
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
	export default {
		props: ['estado', 'failure', 'errors'],

		data() {
			return {
				draft: {},
			}
		},

		methods: {
			store() {
				this.$emit('store', this.estado)
			},
			edit() {
				this.draft = JSON.parse(JSON.stringify(this.estado))
				this.estado.editing = true				
			},
			update() {
				this.$emit('edit', this.estado)
			},
			remove() {
				this.$emit('remove', this.estado)
			},
			cancel() {
				this.estado.editing = false
				this.$emit('cancel', this.estado, this.draft)
			}
		}
	}
</script>