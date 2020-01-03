/*jshint esversion: 6 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import store from './stores/global-store';

import Toasted from 'vue-toasted';
 
Vue.use(Toasted, {
    position: 'top-right',
    duration: 5000,
    type: 'info',
  });

  /*import VueSocketio from 'vue-socket.io';
  Vue.use(new VueSocketio({
       debug: true,
       connection: 'http://192.168.10.10:8080'
   }));*/

const home = Vue.component('home', require('./components/home.vue').default);
const login = Vue.component('login', require('./components/login.vue').default);
const logout = Vue.component('logout', require('./components/logout.vue').default);
const changePassword = Vue.component('changePassword', require('./components/changepassword.vue').default);
const editUser = Vue.component('editUser', require('./components/edituser.vue').default);
const registerUser = Vue.component('registerUser', require('./components/registerUser.vue').default);
const registerIncomeMovement = Vue.component('registerIncomeMovement', require('./components/registerIncomeMovement.vue').default);
const myWallet = Vue.component('myWallet', require('./components/myWallet.vue').default);
const registerExpense = Vue.component('registerExpense', require('./components/registerExpense.vue').default);
const userStatistics = Vue.component('userStatistics', require('./components/userStatistics.vue').default);
const registerAdminOrOperator = Vue.component('registerAdminOrOperator', require('./components/registerAdminOrOperator.vue').default);
const userList = Vue.component('userList', require('./components/userList.vue').default);
const statistics = Vue.component('statistics', require('./components/statistics.vue').default);

const routes = [
  // Define Routes
  { path: '/', component: home, name: 'home'},
  { path: '/login', component: login, name: 'login'},
  { path: '/logout', component: logout, name: 'logout'},
  { path: '/changePassword', component: changePassword, name: 'changePassword'},
  { path: '/editUser', component: editUser, name: 'editUser'},
  { path: '/registerUser', component: registerUser, name: 'registerUser'},
  { path: '/registerIncomeMovement', component: registerIncomeMovement, name: 'registerIncomeMovement'},
  { path: '/myWallet', component: myWallet, name: 'myWallet'},
  { path: '/registerExpense', component: registerExpense, name: 'registerExpense'},
  { path: '/userStatistics', component: userStatistics, name: 'userStatistics'},
  { path: '/registerAdminOrOperator', component: registerAdminOrOperator, name: 'registerAdminOrOperator'},
  { path: '/userList', component: userList, name: 'userList'},
  { path: '/statistics', component: statistics, name: 'statistics'},

];

const router = new VueRouter({
  routes:routes
});

const app = new Vue({
  router,
  data: {
    message: ''
  },
  store,
  /*sockets: {
    connect() {
      console.log(`Socket connected with ID: ${this.$socket.id}`);
    }

  },*/
  methods: {

  },
  created() {
    console.log('-----');
    this.$store.commit('loadTokenAndUserFromSession');
    console.log(this.$store.state.user);

  }
}).$mount('#app');



