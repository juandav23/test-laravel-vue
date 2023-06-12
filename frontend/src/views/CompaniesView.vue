<script>
import { onMounted, reactive, ref, inject } from 'vue'
import axios from 'axios'

export default {
  setup() {
    const list = reactive({ data: [] })
    const errormsg = ref('')
    const swal = inject('$swal')

    const record = reactive({
      id: '',
      name: '',
      address: '',
      budget: '',
      zip_code: ''
    })

    onMounted(() => {
      getList()
    })

    const loadRecord = (company) => {
      record.id = company.id
      record.name = company.name
      record.address = company.address
      record.budget = company.budget
      record.zip_code = company.zip_code
    }

    const submitForm = async (e) => {
      errormsg.value = ''
      e.preventDefault()

      try {
        if (record.id) {
          await axios.put(`company/${record.id}`, record)
        } else {
          await axios.post('company', record)
        }

        getList()

        record.name = ''
        record.address = ''
        record.budget = ''
        record.zip_code = ''
      } catch (error) {
        console.info(error.response.data.errors)
        errormsg.value = error.response.data.errors

        swal.fire({
          icon: 'error',
          //title: 'Oops...',
          text: error.response.data.errors
        })
      }
    }

    const getList = async () => {
      const response = await axios.get('company')
      list.data = response.data
      console.info(list.data)
    }

    return { record, submitForm, list, errormsg, loadRecord }
  }
}
</script>

<template>
  <div>
    <h1 class="title">Companies</h1>

    <div class="content">
      <div class="create-new">
        <label class="subtitle">Create New Company</label>

        <form @submit="submitForm">
          <p class="form-group">
            <label for="name">Company Name</label>
            <input v-model="record.name" type="text" required />
          </p>
          <p class="form-group">
            <label for="address">Address</label>
            <input v-model="record.address" type="text" required />
          </p>
          <p class="form-group">
            <label for="budget">Budget</label>
            <input v-model="record.budget" type="number" required />
          </p>
          <p class="form-group">
            <label for="zip_code">Zip Code</label>
            <input v-model="record.zip_code" type="number" required />
          </p>

          <div>
            <button class="primary" type="submit">Create New Client</button>
          </div>
          <div class="error-area">
            <span class="error">{{ errormsg }}</span>
          </div>

          <input v-model="record.id" type="text" />
        </form>
      </div>

      <div class="list">
        <ul class="list">
          <li v-for="company in list.data" :key="company.id">
            <p>
              <a href="#" @click="loadRecord(company)">
                {{ company.name }}
              </a>
            </p>
            <span>Budget: ({{ company.budget }})</span> -
            <span>Open Positions: ({{ company.open_positions }})</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.content {
  display: flex;
  .create-new {
    width: 35%;
    padding: 20px;
    border: 1px solid #ccc6;
    border-radius: 5px;
    margin-right: 35px;

    .subtitle {
      margin-bottom: 20px;
      display: block;
    }
  }

  .error-area {
    width: 100%;
    padding-top: 40px;

    span.error {
      color: red;
      font-size: 0.8rem;
    }
  }
}
</style>
