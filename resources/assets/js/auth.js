let user = {
	name: null,
	username: null,
	authenticated: false
}

export default {
	user: user,
	check () {
		if (user.name === null && user.username === null)
		{
			axios.get('api/user')
			.then( response => {
				this.user = response.data
				this.user.authenticated = true
			})
			console.log(user)
		}
	},
	signin (username, password) {
		axios.post('login', {
			username: username,
			password: password
		})
		.then( response => {
			this.$router.push('/home')
		})
		.catch( error => {

		})
	},
	signout () {
		axios.post('logout')
        .then( response => {
            this.$router.push('/login')
        })
	}
}