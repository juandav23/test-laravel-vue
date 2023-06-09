<script>
import { onMounted, reactive, ref, inject } from 'vue'
import axios from 'axios'

export default {
  setup() {
    const swal = inject('$swal')
    const list = reactive({ data: [] })
    const errormsg = ref('')

    const record = reactive({
      id: '',
      name: '',
      birth_date: '',
      skills: '',
      education_level: ''
    })

    onMounted(() => {
      getList()
    })

    const submitForm = async (e) => {
      errormsg.value = ''
      e.preventDefault()

      try {
        await axios.post('person', record)
        getList()

        record.name = ''
        record.birth_date = ''
        record.skills = ''
        record.education_level = ''
      } catch (error) {
        console.info(error.response.data.errors)
        errormsg.value = error.response.data.errors
      }
    }

    const deletePerson = async (id) => {
      try {
        const response = await axios.delete(`person/${id}`)
        if (response.data.status === true) {
          swal.fire({
            icon: 'success',
            text: 'Person deleted successfully'
          })
        }
      } catch (error) {
        console.info(error.response?.data?.errors)
        swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: error.response.data.errors
        })
      }
    }

    const getList = async () => {
      const response = await axios.get('person')
      list.data = response.data
      console.info(list)
    }

    const getAge = (dateString) => {
      var today = new Date()
      var birthDate = new Date(dateString)
      var age = today.getFullYear() - birthDate.getFullYear()
      var m = today.getMonth() - birthDate.getMonth()
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--
      }
      return age
    }

    return { record, submitForm, list, errormsg, getAge, deletePerson }
  }
}
</script>

<template>
  <div>
    <h1 class="title">People</h1>

    <div class="content">
      <div class="create-new">
        <label class="subtitle">Create New Person</label>
        <form @submit="submitForm">
          <p class="form-group">
            <label for="name">Client Name</label>
            <input v-model="record.name" type="text" required />
          </p>
          <p class="form-group">
            <label for="birth_date">Birth Date</label>
            <input v-model="record.birth_date" type="date" required />
          </p>
          <p class="form-group">
            <label for="education_level">Education Level</label>
            <input v-model="record.education_level" type="text" required />
          </p>

          <p class="form-group">
            <label for="skills">Skills</label>
            <textarea v-model="record.skills" type="text"></textarea>
          </p>

          <div>
            <button class="primary" type="submit">Create New Client</button>
          </div>
          <div class="error-area">
            <span class="error">{{ errormsg }}</span>
          </div>
        </form>
      </div>

      <div class="list">
        <ul class="list">
          <li class="person-info" v-for="client in list.data" :key="client.id">
            <div>
              <p>{{ client.name }} - {{ getAge(client.birth_date) }} years</p>
              <span>Education Level: ({{ client.education_level }})</span><br />
              <span>Skills: ({{ client.skills }})</span><br />
              <span>Available: ({{ client.available ? 'Yes' : 'No' }})</span>
            </div>
            <div class="actions">
              <button class="primary delete" title="Delete Person" @click="deletePerson(client.id)">
                Delete
              </button>
            </div>
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
    width: 36%;
    padding: 20px;
    border: 1px solid #ccc6;
    border-radius: 5px;
    margin-right: 40px;
    .subtitle {
      margin-bottom: 20px;
      display: block;
    }
  }

  .person-info {
    display: flex;
    justify-content: space-between;
  }

  .delete {
    color: white;
    background-color: red;
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
