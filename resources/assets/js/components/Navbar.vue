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
                <router-link to="/login" class="nav-item is-tab is-hidden-mobile" v-if="!user.authenticated">Login</router-link>
                <slot name="logout" v-if="user.authenticated"></slot>
                <a class="nav-item"><span class="icon"></span></a>
            </div>            
        </nav>
    </div>
</template>
<script>
import auth from '../auth'
    export default {        
        data() { 
            return { 
                user: auth.user
            }
        },
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