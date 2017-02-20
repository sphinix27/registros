<template>
    <aside class="app-sidebar is-hidden-mobile" v-if="user.authenticated">
    	<div class="area">
    		
    	</div>
    	<nav class="main-menu">
            <ul>
                <li>
                    <router-link to="/home" >
                        <i class="fa fa-home fa-2x"></i>
                        <span class="nav-text">Inicio</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/usuarios" v-if="user.role === 'admin'">
                        <i class="fa fa-user fa-2x"></i>
                        <span class="nav-text">Usuarios</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/estados" v-if="user.role === 'admin'">
                        <i class="fa fa-briefcase fa-2x"></i>
                        <span class="nav-text">Estados</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/importar" v-if="user.role === 'admin'">
                        <i class="fa fa-upload fa-2x"></i>
                        <span class="nav-text">Importar</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/delitos" v-if="user.role === 'admin'">
                        <i class="fa fa-balance-scale fa-2x"></i>
                        <span class="nav-text">Delitos</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/turnos" >
                        <i class="fa fa-list-ol fa-2x"></i>
                        <span class="nav-text">Turnos</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/registros" >
                        <i class="fa fa-book fa-2x"></i>
                        <span class="nav-text">Registros</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/about" >
                        <i class="fa fa-book fa-2x"></i>
                        <span class="nav-text">About</span>
                    </router-link>
                </li>
            </ul>
    		<slot name="logout"></slot>              
        </nav>
    </aside>
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
.app-sidebar {
  position: absolute;  
  width: 60px;
  height: calc(100% - 50px);
  z-index:1000;
}

.fa {
position: relative;
display: table-cell;
width: 60px;
height: 36px;
text-align: center;
vertical-align: middle;
font-size:20px;
}


.main-menu:hover,nav.main-menu.expanded {
width:250px;
overflow:visible;
}

.main-menu {
background:#fbfbfb;
border-right:1px solid #e5e5e5;
position:absolute;
top:0;
bottom:0;
height:100%;
left:0;
width:60px;
overflow:hidden;
-webkit-transition:width .05s linear;
transition:width .05s linear;
-webkit-transform:translateZ(0) scale(1,1);
z-index:1000;
}

.main-menu>ul {
margin:7px 0;
}

.main-menu li {
position:relative;
display:block;
width:250px;
}

.main-menu li>a {
position:relative;
display:table;
border-collapse:collapse;
border-spacing:0;
color:#999;
 font-family: arial;
font-size: 14px;
text-decoration:none;
-webkit-transform:translateZ(0) scale(1,1);
-webkit-transition:all .1s linear;
transition:all .1s linear;
  
}

.active {
  color:#fff !important;
  background-color:#20a0ff;
}

.main-menu .nav-icon {
position:relative;
display:table-cell;
width:60px;
height:36px;
text-align:center;
vertical-align:middle;
font-size:18px;
}

.main-menu .nav-text {
position:relative;
display:table-cell;
vertical-align:middle;
width:190px;
  font-family: 'Titillium Web', sans-serif;
}

.main-menu>ul.logout {
position:absolute;
left:0;
bottom:0;
}

.no-touch .scrollable.hover {
overflow-y:hidden;
}

.no-touch .scrollable.hover:hover {
overflow-y:auto;
overflow:visible;
}

a:hover,a:focus {
text-decoration:none;
}

nav {
-webkit-user-select:none;
-moz-user-select:none;
-ms-user-select:none;
-o-user-select:none;
user-select:none;
}

nav ul,nav li {
outline:0;
margin:0;
padding:0;
}
.main-menu li:hover>a,nav.main-menu li.active>a,.dropdown-menu>li>a:hover,.dropdown-menu>li>a:focus,.dropdown-menu>.active>a,.dropdown-menu>.active>a:hover,.dropdown-menu>.active>a:focus,.no-touch .dashboard-page nav.dashboard-menu ul li:hover a,.dashboard-page nav.dashboard-menu ul li.active a {
color:#fff;
background-color:#20a0ff;
}
.area {
float: left;
background: #e2e2e2;
width: 100%;
height: 100%;
}
@font-face {
  font-family: 'Titillium Web';
  font-style: normal;
  font-weight: 300;
  src: local('Titillium WebLight'), local('TitilliumWeb-Light'), url(http://themes.googleusercontent.com/static/fonts/titilliumweb/v2/anMUvcNT0H1YN4FII8wpr24bNCNEoFTpS2BTjF6FB5E.woff) format('woff');
}

</style>