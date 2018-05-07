
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.moment = require('moment');
window.Vue = require('vue');
window.swal  = require('sweetalert2');
window.VueColor = require('vue-color');
// window.CKEDITOR = require( 'ckeditor' );


import Element from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en';
import VueRouter from 'vue-router';
import VModal from 'vue-js-modal'
import CKEDITOR from 'ckeditor'
// import Ckeditor from 'vue-ckeditor2'
import VueQuillEditor from 'vue-quill-editor'
import Avatar from 'vue-avatar'
// Require styles
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import Photoshop from 'vue-color'
import VueCarousel from 'vue-carousel';
import Vuetify from 'vuetify';

Vue.use(Vuetify);
Vue.use(VueCarousel);
Vue.use(Element, { locale });
Vue.use(VModal);
Vue.use(VModal);
Vue.use(VueQuillEditor, /* { default global options } */);
Vue.component('avatar',Avatar);

// Classic Editor
//ClassicEditor
//  .create( document.querySelector( '#editor' ) )
//  .catch( error => {
//      console.error( error );
//} );

// Moment
  Vue.filter('diffInDays', function(value, start){
    var totalSeconds = Math.abs(Date.parse(value) - Date.parse(start));
    
    var hours = (totalSeconds / 1000) / 3600;
    var minutes = ((totalSeconds / 1000) / 60) % 60;
    var seconds = ((totalSeconds / 1000) % 3600) % 60;

    return Math.ceil(hours) + 'HRS ' + Math.ceil(minutes) + 'MINS ' + Math.ceil(seconds) + 'SECS';
    // return totalSeconds / 3600;
    // return moment.duration(Date.parse(value) - Date.parse(start)).asHours();
  })

  Vue.filter('formatHuman', function(value) {
    return moment(value).format("MMMM DD, YYYY")
  })

  Vue.filter('momentAgo', function(value){
      var hours = ((Math.abs(Date.parse(value) - Date.now()) / 1000) /3600) * -1;
      return moment.duration(hours, 'hours').humanize();
  })
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//  Vue Components Path
//  Header
  Vue.component('buzzheader', require('./components/header/Index.vue'));

//  Sidebar
  Vue.component('buzzsidebar', require('./components/sidebar/Index.vue'));

// Dashboard
  Vue.component('dashboard', require('./components/dashboard/Index.vue'));

// Projects
  Vue.component('projects', require('./components/projects/Index.vue'));

// Clients
  Vue.component('clients', require('./components/clients/Index.vue'));

// Clients
  Vue.component('client-details', require('./components/clients/Details.vue'));

// Calendar
  Vue.component('events', require('./components/calendar/Index.vue'));
  Vue.component('add-event', require('./components/calendar/AddEvent.vue'));

// Templates
  Vue.component('templates', require('./components/templates/Index.vue'));

// Templates
  Vue.component('milestone-template', require('./components/milestone/Index.vue'));

// Forms
  Vue.component('buzz-forms', require('./components/forms/Index.vue'));

// Invoices
  Vue.component('invoices', require('./components/invoices/Index.vue'));
  Vue.component('invoice-form', require('./components/invoices/form/Index.vue'));

// Payments
  Vue.component('payments', require('./components/payments/Index.vue'));

// Timers
  Vue.component('timers', require('./components/timers/Index.vue'));

// Cloud
  Vue.component('cloud', require('./components/cloud/Index.vue'));

// Teams
  Vue.component('teams', require('./components/teams/Index.vue'));

// Chat
  Vue.component('chat', require('./components/chat/Index.vue'));

// Reports
  Vue.component('reports', require('./components/reports/Index.vue'));

// Supports
  Vue.component('supports', require('./components/supports/Index.vue'));

// Bugs
  Vue.component('bugs', require('./components/bugs/Index.vue'));

// Services
  Vue.component('services', require('./components/services/Index.vue'));

// Project-HQ
  Vue.component('project-hq', require('./components/projects/project-hq/Index.vue'));
  Vue.component('hq-menu', require('./components/projects/project-hq/HqMenu.vue'));

  // Settings
  Vue.component('settings', require('./components/settings/Index.vue'));

// Common Files
    Vue.component('page-header', require('./components/common/PageHeader.vue'));  

// Testing
  Vue.component('profile', require('./components/teams/profile/Index.vue'));

// Ckeditor
  Vue.component('ckeditor', require('./components/common/Ckeditor.vue'));

  $(document).ready(function(){
  // Push menu
    $("#toggleBuzzMenu").click(function(){
        $("body").toggleClass("sidebar-collapse");
    });
  // Offline and Online Status
    $('#switch-btn').click(function() {
      $('#switch-status').toggleClass('offline');
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

  $("document").ready(function() {
    $('.el-select-dropdown__item').on('click', function(e) {
        if($('.el-dropdown-menu').hasClass('member-option-dropdown')) {
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
        {path: '/',component: require('./components/settings/General.vue')},
        {path: '/company',component: require('./components/settings/Company.vue')},
        {path: '/invoice',component: require('./components/settings/Invoice.vue')},
        {path: '/bank',component: require('./components/settings/Payments/Bank.vue')},
        {path: '/cash',component: require('./components/settings/Payments/Cash.vue')},
        {path: '/paypal',component: require('./components/settings/Payments/Paypal.vue')},
        {path: '/email-templates',component: require('./components/settings/EmailTemplates/EmailTemplates.vue')},
        {path: '/support',component: require('./components/settings/Supports/Support.vue')},
        {path: '/form-fields',component: require('./components/settings/FormFields.vue')},
        {path: '/cron',component: require('./components/settings/Cron.vue')},
        {path: '/db-backup',component: require('./components/settings/DbBackup.vue')},
        {path: '/updates',component: require('./components/settings/Updates.vue')},
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
        {path: '/',component: require('./components/projects/project-hq/overview/Index.vue')},
        {path: '/files',component: require('./components/projects/project-hq/files/Index.vue')},
        {path: '/tasks',component: require('./components/projects/project-hq/tasks/Index.vue')},
  //       {path: '/tasks/new',component: require('./components/projects/project-hq/task/Create')},
  //       {path: '/tasks/update/:id',component: require('./components/projects/project-hq/task/Update'), props: true},
        {path: '/milestones',component: require('./components/projects/project-hq/milestones/Index.vue')},
  //       {path: '/reports',component: require('./components/projects/project-hq/report/index.vue')},

  //       // {path: '/calendar',component: require('./components/projects/project-hq/calendar/index.vue')},
        {path: '/messages',component: require('./components/projects/project-hq/messages/Index.vue')},
        {path: '/invoices',component: require('./components/projects/project-hq/invoices/Index.vue')},
  //       {path: '/invoices/:id',component: require('./components/projects/project-hq/invoice/Invoice'), props: true},
        {path: '/members',component: require('./components/projects/project-hq/members/Index.vue')},
        {path: '/timer',component: require('./components/projects/project-hq/timer/Index.vue')},
        {path: '/reports',component: require('./components/projects/project-hq/reports/Index.vue')},

      ],
      linkActiveClass: 'active'
    });

    buzzcrm['router'] = router;
  }

const app = new Vue(buzzcrm)