<template>
	<div class="container" style="padding-top: 50px;">
	    <div class="row">
	        <div class="column is-8 is-offset-2">
	            <div class="panel">
	                <div class="panel-heading">Login</div>
	                	<div class="panel-block">
	                		<form @submit.prevent="login">
		                        <div class="column is-10 is-offset-1">
		                            <label for="username" class="label">Username</label>
		                                <p class="controln">
		                                    <input class="input" :class="[ errors ? 'is-danger' : '' ]" name="username" v-model="username" required autofocus>
		                                </p>
		                                <span class="help is-danger" v-if="errors">
		                                    {{ errors }}
		                                </span>
		                        </div>

		                        <div class="column is-10 is-offset-1">
		                            <label for="password" class="label">Password</label>
		                                <p class="control">
		                                    <input type="password" class="input" name="password" v-model="password" required>
		                                </p>
		                        </div>

	                            <div class="column is-10 is-offset-1">
	                                <button class="button is-primary" >
	                                    Login
	                                </button>
	                            </div>
	                        </form>
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
				errors: ''
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
					this.errors = errors.response.data.username
				})
			}
		}
	}
</script>