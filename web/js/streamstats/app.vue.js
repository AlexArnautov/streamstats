import {createApp} from 'vue/dist/vue.esm-bundler';

import App from './App.vue'
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';

const app = createApp(App)
app.use(Antd);

app.mount('#app');