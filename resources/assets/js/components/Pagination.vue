<template>
	<nav class="pagination">
		<div class="nav-left">
		    <span>Mostrando {{ pagination.from }} a {{ pagination.to }} de {{ pagination.total }} registros.</span>
		</div>
		<div class="nav-center">
			<ul>
				<li v-if="pagination.current_page > 1" class="number">
					<a aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)" class="pagination-link">
						<i class="fa fa-angle-left"></i>
					</a>
				</li>
				<li v-for="page in pagesNumber" class="number">
					<a @click.prevent="changePage(page)" v-bind:class="[ page == isActived ? 'active' : '']">{{ page }}</a>
				</li>
				<li v-if="pagination.current_page < pagination.last_page" class="number">
					<a aria-label="Next"
						@click.prevent="changePage(pagination.current_page + 1)" class="pagination-next">
						<i class="fa fa-angle-right"></i>
					</a>
				</li>
			</ul>
			<p class="control">
			  	<span class="select">
			    	<select v-model.number="pagination.per_page" @change="changePage">
			    		<option v-for="entry in entries" v-bind:value="entry">
			    			{{ entry }} registros.
			    		</option>
			    	</select>
			  	</span>
			</p>
		</div>
		<div class="nav-right">
			<span>Ir a: </span> 
			<p class="control">
				<input class="input" type="text" v-model="to_page" @keyup.enter="changePage(to_page)">
			</p>
		</div>
	</nav>
</template>

<script>
	export default {
		props: [ 'pagination', 'offset' ],

		data() {
			return {
				entries: [
					10, 25, 50, 100 
				],
				to_page: ''
			}
		},

		computed: {
			isActived() {
				return this.pagination.current_page
			},

			pagesNumber() {
				if(!this.pagination.to){
					return []
				}
				let from = this.pagination.current_page - this.offset
				if (from < 1){
					from = 1
				}
				let to = from + (this.offset * 2)
				if (to >= this.pagination.last_page){
					to = this.pagination.last_page
				}
				let pagesArray = []
				while (from <= to){
					pagesArray.push(from)
					from ++
				}
				return pagesArray
			}
		},
		methods: {
			changePage(page) {
				this.pagination.current_page = page
				if(page > this.pagination.last_page)
					this.pagination.current_page = this.pagination.last_page
				this.$emit('change-page', this.pagination.current_page)
			}
		}
	}
</script>
