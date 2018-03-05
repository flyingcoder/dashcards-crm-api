
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Element from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en';
Vue.use(Element, { locale });
import VueRouter from 'vue-router';
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

// Teams
Vue.component('teams', require('./components/teams/index.vue'));

// if(document.getElementById("app-with-routes")) {

//   Vue.use(VueRouter);

//   let router = new VueRouter({
//     routes: [
//       {path: '/',component: require('./components/projects/project-hq/overview/Index')},
//       {path: '/files',component: require('./components/projects/project-hq/file/Index')},
//       {path: '/tasks',component: require('./components/projects/project-hq/task/Index')},
//       {path: '/tasks/new',component: require('./components/projects/project-hq/task/Create')},
//       {path: '/tasks/update/:id',component: require('./components/projects/project-hq/task/Update'), props: true},
//       {path: '/milestones',component: require('./components/projects/project-hq/milestone/Index')},
//       {path: '/reports',component: require('./components/projects/project-hq/report/Index')},

//       // {path: '/calendar',component: require('./components/projects/project-hq/calendar/Index')},
//       {path: '/messages',component: require('./components/projects/project-hq/message/Index')},
//       {path: '/invoices',component: require('./components/projects/project-hq/invoice/Index')},
//       {path: '/invoices/:id',component: require('./components/projects/project-hq/invoice/Invoice'), props: true},
//       {path: '/members',component: require('./components/projects/project-hq/member/Index')}
//       // {path: '/timers',component: require('./components/projects/project-hq/timers/Index')},
//       //{path: '/reports',component: require('./components/projects/project-hq/report/Index')};

//     ],
//     linkActiveClass: 'active'
//   });

//   app['router'] = router;
// }

const app = new Vue({
    el: '#app'
});



