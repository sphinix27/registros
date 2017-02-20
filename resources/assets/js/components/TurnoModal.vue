<template>
	<vodal :show="show" animation="door" @hide="hide" :width=600 :height=270>
		<div class="modal__header__turno">
			<h4 class="title is-4">Nuevo Turno</h4>
			<span class="vodal-close" @click="hide" />
		</div>
        <div class="box modal__body__turno">
	        <p class="control">
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
	        </p>	        
        </div>
        <div class="modal__footer__turno">
            <ui-button color="primary" @click="store">Crear</ui-button>
            <ui-button slot="cancel" @click="hide">Cancelar</ui-button>
        </div>
    </vodal>
</template>

<script>
import Vodal from 'vodal'
	export default {
		props: ['turno', 'failure', 'errors', 'show'],
		components: { Vodal },
		methods: {
			hide () {
				this.$emit('hide')
			},			
			store() {
				this.$emit('store', this.turno)
			},
			edit() {
				this.$emit('edit', this.turno)
			},
			pickerFormatter(date) {
            	return moment(date).format('DD-MM-YYYY')
        	},
    	},
	}
</script>

<style lang="scss">
@import "~vodal/door.css";
.modal__body__turno {
	width: 100%;
	height: 120px;	
	overflow-x: hidden;
    overflow-y: hidden;
}

.modal__header__turno {
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

.modal__footer__turno {
	height: 70px;
	border-top: 1px solid #eceeef;
	padding: 5px;
}
</style>