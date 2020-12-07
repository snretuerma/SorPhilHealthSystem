
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./custom');

var Chart = require('chart.js');

window.Vue = require('vue');
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';
Vue.use(ElementUI);

locale.use(lang);
import { DataTables, DataTablesServer } from 'vue-data-tables';
Vue.use(DataTables);
Vue.use(DataTablesServer);

// import DataTables and DataTablesServer together
import VueDataTables from 'vue-data-tables';
Vue.use(VueDataTables);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// Vue.component('example-component', require('./components/ExampleComponent.vue'));

//Admin
Vue.component('admindashboard-component', require('./components/admin/chart/AdminDashboard.vue').default);
Vue.component('users-component', require('./components/admin/UserComponent.vue').default);
Vue.component('adminbudget-component', require('./components/admin/BudgetComponent.vue').default);
Vue.component('adminpersonnel-component', require('./components/admin/PersonnelComponent.vue').default);
Vue.component('adminpatient-component', require('./components/admin/PatientComponent.vue').default);
Vue.component('adminrecord-component', require('./components/admin/RecordComponent.vue').default);
Vue.component('resetpassadmin-component', require('./components/admin/ResetPasswordComponent.vue').default);

//Users
Vue.component('userdashboard-component', require('./components/user/chart/UserDashboard.vue').default);
Vue.component('summary-component', require('./components/user/SummaryComponent.vue').default);
Vue.component('doctors-component', require('./components/user/DoctorComponent.vue').default);
Vue.component('record-component', require('./components/user/RecordContainer.vue').default);
Vue.component('restore-component', require('./components/user/RestoreComponent.vue').default);
Vue.component('resetpass-component', require('./components/user/ResetPasswordComponent.vue').default);

//Observer
Vue.component('observerdashboard-component', require('./components/observer/chart/ObserverDashboard.vue').default);
Vue.component('observerbudget-component', require('./components/observer/BudgetComponent.vue').default);
Vue.component('observerpersonnel-component', require('./components/observer/PersonnelComponent.vue').default);
Vue.component('observerpatient-component', require('./components/observer/PatientComponent.vue').default);
Vue.component('observerrecord-component', require('./components/observer/RecordComponent.vue').default);
Vue.component('observeruser-component', require('./components/observer/UserComponent.vue').default);
Vue.component('observerresetpassword-component', require('./components/observer/ResetPasswordComponent.vue').default);

//setting
Vue.component('setting-component', require('./components/user/SettingContainer.vue').default);

Vue.component('medical-component', require('./components/user/MedicalRecordComponent.vue').default);
const app = new Vue({
    el: '#app'
});

//require('./components/user/chart/chart');
