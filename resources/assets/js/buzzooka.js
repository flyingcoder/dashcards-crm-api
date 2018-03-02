
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

// Dashboard
Vue.component('dashboard', require('./components/dashboard/index.vue'));

// Projects
Vue.component('projects', require('./components/projects/index.vue'));

// Clients Temporary on Hq Header
Vue.component('clients', require('./components/projects/project-hq/index.vue'));

// Invoices
Vue.component('invoices', require('./components/invoices/form.vue'));

// Payments
Vue.component('payments', require('./components/payments/index.vue'));


const app = new Vue({
    el: '#app'
});

