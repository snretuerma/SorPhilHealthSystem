
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./custom');

window.Vue = require('vue');
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';
Vue.use(ElementUI);

locale.use(lang)
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
Vue.component('patient-component', require('./components/user/PatientComponent.vue').default);

const app = new Vue({
    el: '#app'
});

