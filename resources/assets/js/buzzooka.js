
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

window.moment = require('moment');

Vue.filter('diffInDays', function(value, start){
  return moment.duration(Date.parse(value) - Date.parse(start)).humanize();
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//  Vue Components Path
  //  Header
  Vue.component('buzzheader', require('./components/header/Index.vue'));

  // Dashboard
  Vue.component('dashboard', require('./components/dashboard/index.vue'));

  // Projects
  Vue.component('projects', require('./components/projects/index.vue'));

  // Clients
  Vue.component('clients', require('./components/clients/index.vue'));

  // Invoices
  Vue.component('invoices', require('./components/invoices/index.vue'));

  // Payments
  Vue.component('payments', require('./components/payments/index.vue'));

  // Timers
  Vue.component('timers', require('./components/timers/index.vue'));

  // Teams
  Vue.component('teams', require('./components/teams/index.vue'));

  // Reports
  Vue.component('reports', require('./components/reports/index.vue'));


// Project-HQ
Vue.component('project-hq', require('./components/projects/project-hq/Index'));
Vue.component('hq-menu', require('./components/projects/project-hq/HqMenu'));

// Common Files
Vue.component('page-header', require('./components/common/PageHeader.vue'));

// Testing
// Vue.component('clients', require('./components/projects/project-hq/index.vue'));

// Services
Vue.component('services', require('./components/services/index.vue'));


// Avoid on Closing Templates Dropdown When Clicking Check Boxes
  $("document").ready(function() {
    $('.dropdown-menu').on('click', function(e) {
        if($(this).hasClass('templates')) {
            e.stopPropagation();
        }
    });
  });

let buzzcrm = {
  el: '#app'
};

if(document.getElementById("app-with-routes")) {

  Vue.use(VueRouter);

  let router = new VueRouter({
    routes: [
      {path: '/',component: require('./components/projects/project-hq/overview/Index')},
      {path: '/files',component: require('./components/projects/project-hq/files/Index')},
      {path: '/tasks',component: require('./components/projects/project-hq/tasks/Index')},
//       {path: '/tasks/new',component: require('./components/projects/project-hq/task/Create')},
//       {path: '/tasks/update/:id',component: require('./components/projects/project-hq/task/Update'), props: true},
      {path: '/milestones',component: require('./components/projects/project-hq/milestones/Index')},
//       {path: '/reports',component: require('./components/projects/project-hq/report/Index')},

//       // {path: '/calendar',component: require('./components/projects/project-hq/calendar/Index')},
//       {path: '/messages',component: require('./components/projects/project-hq/message/Index')},
//       {path: '/invoices',component: require('./components/projects/project-hq/invoice/Index')},
//       {path: '/invoices/:id',component: require('./components/projects/project-hq/invoice/Invoice'), props: true},
      {path: '/members',component: require('./components/projects/project-hq/members/Index')}
//       // {path: '/timers',component: require('./components/projects/project-hq/timers/Index')},
//       //{path: '/reports',component: require('./components/projects/project-hq/report/Index')};

    ],
    linkActiveClass: 'active'
  });

  buzzcrm['router'] = router;
}

const app = new Vue(buzzcrm)



