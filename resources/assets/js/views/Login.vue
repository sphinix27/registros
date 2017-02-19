<template>
	<div class="container" style="padding-top: 50px;">
	    <div class="row">
	        <div class="column is-8 is-offset-2">
	            <div class="panel">
	                <div class="panel-heading">Login</div>
	                	<div class="panel-block">        
	                        <div class="column is-10 is-offset-1">
	                            <label for="username" class="label">Username</label>
	                                <p class="controln">
	                                    <input class="input" :class="[ 'errors.username' ? '' : 'is-danger' ]" name="username" v-model="username" required autofocus>
	                                </p>
	                                    <span class="help is-danger" v-if="errors.username">
	                                        <strong>{{ errors.username }}</strong>
	                                    </span>
	                        </div>

	                        <div class="column is-10 is-offset-1">
	                            <label for="password" class="label">Password</label>
	                                <p class="control">
	                                    <input type="password" class="input" :class="[ 'errors.password' ? '' : 'is-danger' ]" name="password" v-model="password" required>
	                                </p>
	                                <span class="help is-danger" v-if="errors.password">
	                                    <strong>{{ errors.password }}</strong>
	                                </span>
	                        </div>

	                            <div class="column is-10 is-offset-1">
	                                <a class="button is-primary" @click.prevent="login">
	                                    Login
	                                </a>
	                            </div>
	                	</div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
import auth from '../auth'
	export default {
		data() {
			return {
				auth: auth,
				username: '',
				password: '',
				errors: {
					username: '',
					password: '',
				}
			}
		},
		methods: {
			login () {
				axios.post('login', {
					username: this.username,
					password: this.password
				})
				.then(response => {
					this.$router.push('/home')
				})
				.catch(errors => {
					console.log(errors.reponse)
				})
			}
		}
	}
</script>