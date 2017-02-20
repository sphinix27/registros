<template>
    <div>
        <nav class="nav has-shadow">
            <div class="nav-left">
                <a class="nav-item">
                    <img src="img/FGEBo.svg" alt="Bulma logo">
                    <div class="is-hidden-mobile">
                        <span class="vue">Fiscalía</span><strong class="admin"> Departamental</strong>
                    </div>
                </a>
            </div>

            <span class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </span>
            <div class="nav-right nav-menu is-hidden-tablet">
                <router-link to="/login" class="nav-item is-tab is-hidden-tablet" v-if="!user.authenticated">Login</router-link>
                <template v-if="user.authenticated">
                    <router-link to="/home" class="nav-item is-hidden-tablet">Inicio</router-link>
                    <router-link to="/usuarios" class="nav-item is-hidden-tablet" v-if="user.role === 'admin'">Usuarios</router-link>
                    <router-link to="/estados" class="nav-item is-hidden-tablet" v-if="user.role === 'admin'">Estados</router-link>
                    <router-link to="/importar" class="nav-item is-hidden-tablet" v-if="user.role === 'admin'">Importar</router-link>
                    <router-link to="/delitos" class="nav-item is-hidden-tablet" v-if="user.role === 'admin'">Delitos</router-link>
                    <router-link to="/turnos" class="nav-item is-hidden-tablet">Turnos</router-link>
                    <router-link to="/registros" class="nav-item is-hidden-tablet">Registros</router-link>
                    <slot name="hidden"></slot>
                </template>
            </div>
            <div class="nav-right nav-menu">
                <ui-progress-circular color="multi-color" v-show="loading"></ui-progress-circular>
                <router-link to="/login" class="nav-item is-tab is-hidden-mobile" v-if="!user.authenticated">Login</router-link>
                <slot name="logout" v-if="user.authenticated"></slot>
            </div>            
        </nav>
        <ui-preloader :show="loading"></ui-preloader>
    </div>
</template>
<script>
import auth from '../auth'
    export default {        
        data() { 
            return { 
                loading: false,
                user: auth.user
            }
        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                vm.loading = true
                setTimeout(
                    () => {
                        vm.loading = false
                    }, 500)
            })
        },
        watch: {
            $route () {
                this.loading = true
                setTimeout(
                    () => {
                        this.loading = false
                }, 500)
            }
      }
    }
</script>
<style lang="scss">
.vue {
    margin-left: 10px;
    color: #20a0ff;
}
.admin {
    color: #28374B;
}
</style>