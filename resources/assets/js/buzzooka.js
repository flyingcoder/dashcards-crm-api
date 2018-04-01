
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.moment = require('moment');
window.Vue = require('vue');
window.swal  = require('sweetalert2')

import Element from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en';
import VueRouter from 'vue-router';
import VModal from 'vue-js-modal'
import Ckeditor from 'vue-ckeditor2'
import VueQuillEditor from 'vue-quill-editor'
// Require styles
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'

Vue.use(Element, { locale });
Vue.use(VModal);
Vue.use(Ckeditor);
Vue.use(VModal);
Vue.use(VueQuillEditor, /* { default global options } */)

//window.CKEDITOR = require( 'ckeditor' );

// Classic Editor
//ClassicEditor
//  .create( document.querySelector( '#editor' ) )
//  .catch( error => {
//      console.error( error );
//} );

// Moment
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
  Vue.component('buzzheader', require('./components/header/index.vue'));

//  Sidebar
  Vue.component('buzzsidebar', require('./components/sidebar/index.vue'));

// Dashboard
  Vue.component('dashboard', require('./components/dashboard/index'));

// Projects
  Vue.component('projects', require('./components/projects/index'));

// Clients
  Vue.component('clients', require('./components/clients/index.vue'));

// Calendar
  Vue.component('events', require('./components/calendar/index.vue'));
  Vue.component('add-event', require('./components/calendar/AddEvent.vue'));

// Templates
  Vue.component('templates', require('./components/templates/index.vue'));

// Forms
  Vue.component('buzz-forms', require('./components/forms/index.vue'));

// Invoices
  Vue.component('invoices', require('./components/invoices/index.vue'));

// Payments
  Vue.component('payments', require('./components/payments/index.vue'));

// Timers
  Vue.component('timers', require('./components/timers/index.vue'));

// Cloud
  Vue.component('cloud', require('./components/cloud/index.vue'));

// Teams
  Vue.component('teams', require('./components/teams/index.vue'));

// Chat
  Vue.component('chat', require('./components/chat/index.vue'));

// Reports
  Vue.component('reports', require('./components/reports/index.vue'));

// Supports
  Vue.component('supports', require('./components/supports/index.vue'));

// Bugs
  Vue.component('bugs', require('./components/bugs/index.vue'));

// Services
  Vue.component('services', require('./components/services/index'));

// Project-HQ
  Vue.component('project-hq', require('./components/projects/project-hq/index'));
  Vue.component('hq-menu', require('./components/projects/project-hq/HqMenu'));

// Common Files
    Vue.component('page-header', require('./components/common/PageHeader.vue'));  

// Testing
  Vue.component('profile', require('./components/teams/profile/index.vue'));

// Push menu
  $(document).ready(function(){
    $("#toggleBuzzMenu").click(function(){
      console.log('Test!!');
        $("body").toggleClass("sidebar-collapse");
    });
  });

// Avoid on Closing Templates Dropdown When Clicking Check Boxes
  $("document").ready(function() {
    $('.dropdown-menu').on('click', function(e) {
        if($(this).hasClass('templates')) {
            e.stopPropagation();
        }
    });
  });

// Full Calendar 
    var date = new Date(),
    d = date.getDate(),
    m = date.getMonth(),
    y = date.getFullYear(),
    started,
    categoryClass;

    var calendar = $('#calendar').fullCalendar({
      header: {
        left: 'prev',
        center: 'title',
        right: 'next'
      },
    /* 
      selectable: true,
      selectHelper: true,
      select: function(start, end, allDay) {

        $('#fc_create').click();
        started = start;
        ended = end;

        $(".addNote").on("click", function() {
          var title = $("#note").val();
          if (end) {
            ended = end;
          }

          categoryClass = $("#event_type").val();

          if (title) {
            calendar.fullCalendar('renderEvent', {
                title: title,
                start: started,
                end: end,
                allDay: allDay
              },
              true // make the event "stick"
            );
          }

          $('#note').val('');

          calendar.fullCalendar('unselect');

          $('.cancel').click();

          return false;
        });
      },
      eventClick: function(calEvent, jsEvent, view) {
        $('#fc_edit').click();
        $('#noteEdit').val(calEvent.title);

        categoryClass = $("#event_type").val();

        $(".save").on("click", function() {
          calEvent.title = $("#noteEdit").val();

          calendar.fullCalendar('updateEvent', calEvent);
          $('.cancel2').click();
        });

        calendar.fullCalendar('unselect');
      },
      editable: true,
      events: [{
        title: 'Start Date',
        start: new Date(y, m, 1)
      }] */
    });

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import toastr from 'toastr';

import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '6857db1d25c87cb2e20d',
    cluster: 'ap1',
    encrypted: true
});

let buzzcrm = {
  el: '#app',
  data: {
      onlineUsers: []
  },
  created() {
      let companyid = window.Current.company.id;

      window.Echo.join('user.login.' + companyid)
        .here(users => {
          console.log('here');
          this.onlineUsers = users;
        })
        .joining(user => {
          console.log('joining');
          this.onlineUsers.push(user);
        })
        .leaving(user => {
          console.log('leaving');
          this.onlineUsers.push(this.onlineUsers.indexOf(user), 1);
        })
        .listen('UserLogin', e => {
          toastr.info(e.user.first_name+' is online!');
        });
  }
};

// Settings
  if(document.getElementById("settings-routes")) {

    Vue.use(VueRouter);

    let router = new VueRouter({
      routes: [
        // {path: '/',component: require('./components/settings/General.vue')},
      ],
      linkActiveClass: 'active'
    });

    buzzcrm['router'] = router;
  }


// Project HQ
  if(document.getElementById("app-with-routes")) {

    Vue.use(VueRouter);

    let router = new VueRouter({
      routes: [
        {path: '/',component: require('./components/projects/project-hq/overview/index')},
        {path: '/files',component: require('./components/projects/project-hq/files/index')},
        {path: '/tasks',component: require('./components/projects/project-hq/tasks/index')},
  //       {path: '/tasks/new',component: require('./components/projects/project-hq/task/Create')},
  //       {path: '/tasks/update/:id',component: require('./components/projects/project-hq/task/Update'), props: true},
        {path: '/milestones',component: require('./components/projects/project-hq/milestones/index')},
  //       {path: '/reports',component: require('./components/projects/project-hq/report/index')},

  //       // {path: '/calendar',component: require('./components/projects/project-hq/calendar/index')},
  //       {path: '/messages',component: require('./components/projects/project-hq/message/index')},
  //       {path: '/invoices',component: require('./components/projects/project-hq/invoice/index')},
  //       {path: '/invoices/:id',component: require('./components/projects/project-hq/invoice/Invoice'), props: true},
        {path: '/members',component: require('./components/projects/project-hq/members/index')},
  //       // {path: '/timers',component: require('./components/projects/project-hq/timers/index')},
        {path: '/reports',component: require('./components/projects/project-hq/reports/index')},

      ],
      linkActiveClass: 'active'
    });

    buzzcrm['router'] = router;
  }

const app = new Vue(buzzcrm)