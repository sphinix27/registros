<template>
    <div class="row">
        <user-modal
            :user="user"
            :failure="failure"
            :errors="errors"
            :show="show"
            @hide="hide"
            @store="storeUser"
            @edit="updateUser"
            >        
        </user-modal>
        <div class="content column is-12">
            <div class="title is-2">Users</div>
            <div class="nav menu">
                <div class="container">
                    <div class="nav-left">
                        <a id="add2" class="nav-item is-tab is-boxed" @click="open" ref="new">
                            <span class="icon-btn">
                                <i class="fa fa-plus"></i>
                            </span>
                        </a>
                        <ui-tooltip trigger="new" position="top center">Nuevo</ui-tooltip>
                        <slot></slot>                        
                    </div>                
                </div>
            </div>
        </div>
        <div class="row">
            <pagination 
                :pagination="pagination"
                :offset="offset"
                @change-page="changePage"></pagination>
            <div class="table-responsive">        
                <table class="table is-bordered is-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th colspan="2">Enlaces</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr is="user-row"
                            v-for="user in users"
                            :user="user"
                            :failure="failure"
                            :errors="errors"
                            @edit="edit"
                            @remove="removeUser">
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination 
                :pagination="pagination"
                :offset="offset"
                @change-page="changePage"></pagination>
        </div>
    </div>
</template>

<script>
import Pagination from '../components/Pagination'
import UserRow from '../components/UserRow'
import UserModal from '../components/UserModal'

export default {
    components: {
        Pagination, UserRow, UserModal
    },
    data () {
        return {
            failure: false,
            show: false,
            user: {
                name: '',
                username: '',
                password: '',
                role: false,
            },
            users: [],
            errors: [],
            pagination: {
                total: 0, per_page: 10,
                from: 1, to: 0,
                current_page: 1
            },
            offset: 4,
        }
    },
    mounted () {
        this.fetchUsers(this.pagination.current_page)
    },
    methods: {
        fetchUsers (page) {
            this.users = []
            axios.get('api/user', {
                params: {
                    page: page,
                    limit: this.pagination.per_page
                }
            })
            .then( response => {
                this.users = response.data.data.map(user => {
                    user.editing = false
                    return user
                })
                this.pagination = response.data
            })
        },
        storeUser (user) {
            axios.post('api/user', user)
            .then(response => {                    
                this.user = response.data.user
                this.$message({
                    type: 'success',
                    message: 'Registro agregado correctamente'
                });
                this.users.push(this.user)
                this.hide()
            }).catch( error => {
                this.failure = true
                this.errors = error.response.data.errors
                this.$message({
                    message: 'Ocurrió un error',
                    type: 'error'
                });
            })
        },
        updateUser (user) {
            axios.patch('api/user/'+ user.id, user)
            .then(response => {
                user.editing = false
                this.$message({
                    type: 'success',
                    message: 'Registro actualizado correctamente'
                });
                this.hide()
            }).catch( error => {
                this.failure = true
                this.errors = error.response.data.errors
                this.$message({
                        message: 'Ocurrió un error',
                        type: 'error'
                    });
            })
        },
        removeUser (user) {
            this.$confirm('Esto borrará permanentemente el registro. Desea continuar?', 'Advertencia', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancelar',
                type: 'warning'
            }).then(() => {
                let index = this.users.indexOf(user)
                this.users.splice(index, 1)
                axios.delete('api/user/'+ user.id)
                this.$message({
                    type: 'success',
                    message: 'Borrado completado'
            });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: 'Borrado cancelado'
                });          
            });
        },
        open () {
            this.show = true
        },
        edit (user) {
            this.user = user
            this.user.editing = true
            this.user.password = ''
            this.show = true
        },
        hide () {
            this.user = {
                name: '',
                username: '',
                password: '',
                role: '',
            }
            this.errors = {}
            this.failure = false
            this.show = false
        },
        changePage (page) {
            this.fetchUsers(page)
        }
    }
}
</script>