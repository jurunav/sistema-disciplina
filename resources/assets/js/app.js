
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('categoria', require('./components/Categoria.vue'));
Vue.component('premio', require('./components/Premio.vue'));
Vue.component('disciplina', require('./components/Disciplina.vue'));
Vue.component('sancion', require('./components/Sancion.vue'));
Vue.component('oficial', require('./components/Oficial.vue'));
Vue.component('cadete', require('./components/Cadete.vue'));
Vue.component('merito', require('./components/Merito.vue'));
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('user', require('./components/User.vue'));

const app = new Vue({
    el: '#app',
    data:{
        menu : 0
    }

});
