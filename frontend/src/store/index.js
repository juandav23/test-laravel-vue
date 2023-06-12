import { createStore } from 'vuex'
import axios from 'axios'

// imports from admin folder
import settings from './settings'

axios.defaults.baseURL = 'http://127.0.0.1:8000/api/'
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'
axios.defaults.headers.post['Accept'] = 'application/json;charset=UTF-8'

export default new createStore({
  modules: {
    settings
  }
})
