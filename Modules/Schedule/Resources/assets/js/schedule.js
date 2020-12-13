import Index from './index.vue';

window.Vue = require('vue');

import SuiVue from 'semantic-ui-vue';
import PortalVue from 'portal-vue';

Vue.component('index', require('./index.vue').default);

Vue.use(SuiVue);
Vue.use(PortalVue);

const app = new Vue({
  el: '#app',
  components: {
    Index: Index,
  },
});
