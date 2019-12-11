// Vuex store
import Vue from 'vue'
import store from './store'
import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');


window.Vue = require('vue');
Vue.config.productionTip = false
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 */


Vue.component('messenger', require('./components/MessengerApp.vue').default);

const app = new Vue({
    el: '#app',
    components: {},
    store: store
});