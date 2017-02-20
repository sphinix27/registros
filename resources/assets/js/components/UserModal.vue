<template>
	<vodal :show="show" animation="door" @hide="hide" :width=600 :height=500>
		<div class="modal__header__user">
			<h4 class="title is-4">Usuario</h4>
			<span class="vodal-close" @click="hide" />
		</div>
        <div class="box modal__body__user">
        	<p class="control">
	            <ui-textbox
	                floating-label
	                label="Nombre"
	                name="nombre"
	                help="El nombre completo del Usuario"
	                v-model="user.name"
	                :invalid="failure"><p v-for="error in errors.name">{{ error }}</p>
	            </ui-textbox>
	        </p>
        	<p class="control">
	            <ui-textbox
	                floating-label
	                label="Usuario"
	                name="username"
	                help="El nombre de usuario"
	                v-model="user.username"
	                :invalid="failure"><p v-for="error in errors.username">{{ error }}</p>
	            </ui-textbox>
	        </p>
        	<p class="control">
	            <ui-textbox
	                floating-label
	                label="Password"
	                name="password"
	                type="password"
	                help="La contraseña de la cuenta"
	                v-model="user.password"
	                :invalid="failure"><p v-for="error in errors.password">{{ error }}</p>
	            </ui-textbox>
	        </p>	  
	        <p class="control">
	        	<ui-checkbox
	        		label="Rol"
	        		v-model="user.role"
	        		trueValue="admin"
	        		falseValue="user">Administrador</ui-checkbox>
	        </p>
        </div>
        <div class="modal__footer__user">
        	<template v-if="user.editing">
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
		props: ['user', 'failure', 'errors', 'show'],
		components: { Vodal },
		methods: {
			hide () {
				this.$emit('hide')
			},			
			store() {
				this.$emit('store', this.user)
			},
			edit() {
				this.$emit('edit', this.user)
			},
    	},
	}
</script>

<style lang="scss">
@import "~vodal/door.css";
.modal__body__user {
	width: 100%;
	height: 300px;
	overflow-x: hidden;
    overflow-y: hidden;
}

.modal__header__user {
	align-items: center;
    background-color: #F5F5F5;
    box-shadow: 0 1px 1px rgba(black, 0.16);
    display: block;
    height: 50px;
    padding: 15px;
    position: relative;
    z-index: 0;
    border-bottom: 1px solid #eceeef;
}

.modal__footer__user {
	height: 70px;
	border-top: 1px solid #eceeef;
	padding: 5px;
}
</style>