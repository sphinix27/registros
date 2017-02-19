import VueRouter from 'vue-router';

let routes = [
    {
        path: '/home',
        component: require('./views/Home'),
    },
    {
        path: '/delitos',
        component: require('./views/Delito'),
    },
    {
        path: '/importar',
        component: require('./views/ImportDelito'),
    },
    {
        path: '/estados',
        component: require('./views/Estado'),
    },
    {
        path: '/registros',
        component: require('./views/Registro'),
    },
    {
        path: '/login',
        component: require('./views/Login'),
    },
];

export default new VueRouter({
    routes,
    linkActiveClass: 'active',
});