import VueRouter from 'vue-router';
import auth from './auth'
let routes = [
    {
        path: '/home',
        component: require('./views/Home'),
    },
    {
        path: '/delitos',
        component: require('./views/Delito'),
        beforeEnter: (to, from, next) => {
            if(auth.user.role === 'admin')
                next()
            else
                next('/home')
        }
    },
    {
        path: '/importar',
        component: require('./views/ImportDelito'),
        beforeEnter: (to, from, next) => {
            if(auth.user.role === 'admin')
                next()
            else
                next('/home')
        }
    },
    {
        path: '/estados',
        component: require('./views/Estado'),
        beforeEnter: (to, from, next) => {
            if(auth.user.role === 'admin')
                next()
            else
                next('/home')
        }
    },
    {
        path: '/registros',
        component: require('./views/Registro'),
    },
    {
        path: '/login',
        component: require('./views/Login'),
    },
    {
        path: '/turnos',
        component: require('./views/Turno'),
    },
    {
        path: '/turnos/:id/registros',
        component: require('./views/TurnoRegistro'),
    },
    {
        path: '/usuarios',
        component: require('./views/User'),
        beforeEnter: (to, from, next) => {
            if(auth.user.role === 'admin')
                next()
            else
                next('/home')
        }
    },
];

export default new VueRouter({
    routes,
    linkActiveClass: 'active',
});