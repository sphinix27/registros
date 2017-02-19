<template>
        <table class="table is-bordered is-striped">
            <thead>
                <tr>
                	<th v-for="key in columns"
                		@click="sortBy(key)"
                		:class="{ active: sortKey == key }">
                		{{ key | capitalize }}
                		<span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
                		</span>
                	</th>                    
                    <th colspan="2">Enlaces</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="entry in filteredData">
                    <td v-for="key in columns" >
                        <template v-if="strCheck(entry[key])">
                            <span v-for="field in entry[key]">
                                {{ field.nombre }}                                
                            </span>
                        </template>
                        <template v-else>
                            {{ entry[key] }}
                        </template>
            		</td>
                    <td class="is-icon">
                        <a @click="edit(entry)">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="is-icon">
                        <a @click="remove(entry)">
                            <i class="fa fa-trash"></i>
                        </a>            
                    </td>
                </tr>
            </tbody>
        </table>
</template>

<script>
export default {
    props: {
    	data: Array,
    	columns: Array,
    	filterKey: String
    },
    data() {
    	var sortOrders = {}
    	this.columns.forEach(key => {
    		sortOrders[key] = 1
    	})
    	return {
            draft: '',
    		sortKey: '',
    		sortOrders: sortOrders
    	}
    },
    computed: {
    	filteredData() {
    		let sortKey = this.sortKey
    		let filterKey = this.filterKey && this.filterKey.toLowerCase()
    		let order = this.sortOrders[sortKey] || 1
    		let data = this.data
    		if (filterKey) {
    			data = data.filter(row => {
    				return Object.keys(row).some(key => {
    					return String(row[key]).toLowerCase().indexOf(filterKey) > -1
    				})
    			})
    		}
    		if (sortKey) {
    			data = data.slice().sort((a, b) => {
    				a = a[sortKey]
    				b = b[sortKey]
    				return (a === b ? 0 : a > b ? 1: -1) * order
    			})
    		}
    		return data
    	},
    },
    filters: {
        capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1)
        }
    },
    methods: {
        strCheck(str) {
            return !_.isString(str)
        },
    	sortBy(key) {
    		this.sortKey = key
    		this.sortOrders[key] = this.sortOrders[key] * -1
    	},
        edit (entry) {
            this.draft = JSON.parse(JSON.stringify(entry))
            this.$emit('edit', this.draft)
        },
        remove(entry) {
            this.$emit('remove', entry)
        },
    }
}
</script>

<style lang="scss">
.arrow {
  display: inline-block;
  vertical-align: middle;
  width: 0;
  height: 0;
  margin-left: 5px;
  opacity: 0.66;
}

.arrow.asc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-bottom: 4px solid #fff;
}

.arrow.dsc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 4px solid #fff;
}
</style>