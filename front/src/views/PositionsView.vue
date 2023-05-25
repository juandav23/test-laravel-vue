<script>
import { onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import router from '@/router'

export default {
  setup() {
    const list = reactive({ data: [] })
    const companies = reactive({ data: [] })
    const errormsg = ref('')

    const record = reactive({
      id: '',
      company_id: '',
      role: '',
      description: '',
      years_of_experience: '',
      salary: ''
    })

    onMounted(() => {
      getCompanies()
    })

    const submitForm = async (e) => {
      errormsg.value = ''
      e.preventDefault()

      try {
        await axios.post('positions', record)
        getPositions()

        record.description = ''
        record.years_of_experience = ''
        record.salary = ''
      } catch (error) {
        console.info(error.response.data.errors)
        errormsg.value = error.response.data.errors
      }
    }

    const getPositions = async () => {
      if (!record.company_id) return
      const response = await axios.get(`positions/${record.company_id}`)
      list.data = response.data
      console.info(list)
    }

    const getCompanies = async () => {
      const response = await axios.get('company')
      companies.data = response.data
      console.info(list)
    }

    const navigateToPosition = (positionId) => {
      router.push({ path: '/position/' + positionId })
    }

    return { record, submitForm, list, errormsg, getPositions, companies, navigateToPosition }
  }
}
</script>

<template>
  <div>
    <div class="header">
      <h1 class="title">Company:</h1>
      <select v-model="record.company_id" id="company" @change="getPositions">
        <option value="">--</option>
        <option v-for="company in companies.data" :key="company.id" :value="company.id">
          {{ company.name }}
        </option>
      </select>
    </div>

    <div class="content" v-show="record.company_id">
      <div class="positions-view">
        <ul class="list">
          <li v-for="position in list.data" :key="position.id">
            <p class="position-role">
              <a href="#" @click="navigateToPosition(position.id)">
                {{ position.role }}
              </a>
              <span class="open" v-if="position.open">OPEN</span>
              <span class="closed" v-if="!position.open">CLOSED</span>
            </p>
            <span>{{ position.description }}</span
            ><br />
            <span>Years Of Experience: ({{ position.years_of_experience }})</span><br />
            <span>Salary: ({{ position.salary }})</span>
          </li>
        </ul>
      </div>

      <div class="create-new" v-show="record.company_id">
        <label class="subtitle">New Position</label>

        <form @submit="submitForm">
          <p class="form-group">
            <label for="role">Role</label>
            <input v-model="record.role" type="text" required />
          </p>

          <p class="form-group">
            <label for="years_of_experience">Years Of Experience</label>
            <input v-model="record.years_of_experience" type="number" required :max="10" />
          </p>
          <p class="form-group">
            <label for="salary">Salary</label>
            <input v-model="record.salary" type="number" required />
          </p>
          <p class="form-group">
            <label for="description">Description</label>
            <textarea v-model="record.description" type="text"></textarea>
          </p>
          <div>
            <button class="primary" type="submit">Create Position</button>
          </div>
          <div class="error-area">
            <span class="error">{{ errormsg }}</span>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.header {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 45px;
  .title {
    text-align: center;
    margin: 0;
    margin-right: 28px;
  }
  select {
    height: 40px;
    padding: 0px 10px;
    width: 220px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 4px;
  }
}

.content {
  display: flex;
  flex-flow: row wrap;
  flex-grow: 1;
  justify-content: space-between;
  .create-new {
    width: 30%;
    padding: 20px;
    border: 1px solid #ccc6;
    border-radius: 5px;

    .subtitle {
      margin-bottom: 20px;
      display: block;
    }
  }

  .positions-view {
    width: 60%;

    .position-role {
      display: flex;
      justify-content: space-between;
      align-items: center;

      .closed {
        color: white;
        background-color: red;
        padding: 3px 5px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: 600;
      }
      .open {
        color: white;
        background-color: #65c556;
        padding: 3px 9px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: 600;
      }
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
