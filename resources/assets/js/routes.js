import VueRouter from 'vue-router';

let routes = [
    {
        path: '/home',
        component: require('./views/Home')
    },
    {
        path: '/delitos',
        component: require('./views/Delito')
    },
    {
        path: '/registro',
        component: require('./views/Registro')
    },
];

export default new VueRouter({
    routes,
    linkActiveClass: 'active'
});