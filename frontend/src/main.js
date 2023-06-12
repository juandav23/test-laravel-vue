import './assets/main.scss'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import VueSweetAlert from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const app = createApp(App)

app.use(router)
app.use(store)
app.use(VueSweetAlert)

app.mount('#app')
