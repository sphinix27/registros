<template>
	<transition 
		name="fade"
		enter-active-class="fadeInLeft"
		leave-active-class="fadeOutRight">
	<tr>
		<td>{{ user.name }}</td>
		<td>{{ user.username }}</td>
		<td><span class="tag is-info">{{ user.role }}</span></td>
		<td class="is-icon">
		  	<a @click="edit()" ref="edit">
		  	  	<i class="fa fa-edit"></i>
		  	</a>
			<ui-tooltip trigger="edit" position="top center">Editar</ui-tooltip>
		</td>
		<td class="is-icon">
			<template v-if="user.role !== 'admin'">
	        	<a @click="remove()" ref="delete">
			  		<i class="fa fa-trash"></i>
				</a>
				<ui-tooltip trigger="delete" position="top center">Eliminar</ui-tooltip>
			</template>
			<template v-else>
				<a href="#" class="is-disabled">
					<i class="fa fa-trash"></i>
				</a>
			</template>
		</td>		
	</tr>
	</transition>
</template>
<script>
	export default {
		props: ['user', 'failure', 'errors'],

		data() {
			return {
				draft: {},
			}
		},
		methods: {
			edit() {
				this.draft = JSON.parse(JSON.stringify(this.user))
				this.user.editing = true
				this.$emit('edit', this.user)
			},
			remove() {
				this.$emit('remove', this.user)
			}
		}
	}
</script>