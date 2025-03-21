import swal from 'sweetalert'
window.axios = require('axios')
import flatpickr from "flatpickr"

import { createApp } from 'vue';
import TemplateSelect from './components/TemplateSelect.vue';

createApp({
  components: {
    TemplateSelect
  },
}).mount('#content');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'