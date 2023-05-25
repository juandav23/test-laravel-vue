<script>
import { onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import Modal from '../components/ModalCmp.vue'
import axios from 'axios'

export default {
  components: { Modal },
  setup() {
    const route = useRoute()
    const id = route.params.id

    const showModal = ref(false)
    const position = ref({})
    const clients = reactive({ data: [] })

    onMounted(() => {
      getClients()

      if (id) {
        getPosition()
      }
    })

    const saveApplicant = async (client_id) => {
      try {
        const response = await axios.post('positions/applicant', {
          client_id,
          positions_id: position.value.id
        })
        if (response.data.status === true) {
          showModal.value = false
          getPosition()
        }
      } catch (error) {
        console.info(error.response?.data?.errors)
      }
    }

    const deleteApplicant = async (appId) => {
      if (!appId) return
      const response = await axios.delete(`positions/applicant/${appId}`)
      getPosition()
      console.info(response)
    }

    const markAsWinner = async (appId) => {
      if (!appId) return
      const response = await axios.get(`positions/winner/${appId}`)
      getPosition()
      console.info(response)
    }

    const getPosition = async () => {
      if (!id) return
      const response = await axios.get(`positions/position/${id}`)
      position.value = response.data
    }

    const getClients = async () => {
      const response = await axios.get('client')
      clients.data = response.data
    }

    return { position, showModal, clients, saveApplicant, deleteApplicant, markAsWinner }
  }
}
</script>

<template>
  <div>
    <div class="content">
      <div class="positions-view">
        <ul class="list">
          <li>
            <p class="position-role">
              <a href="#">{{ position.role }} </a>
              <span class="open" v-if="position.open">OPEN</span>
              <span class="closed" v-if="!position.open">CLOSED</span>
            </p>
            <span>{{ position.description }}</span>
            <br />
            <span>Years Of Experience: ({{ position.years_of_experience }})</span><br />
            <span>Salary: ({{ position.salary }})</span>
          </li>
        </ul>
      </div>

      <div class="applicants-view">
        <header>
          <h2 class="subtitle">Applicants</h2>
          <button class="primary" id="show-modal" @click="showModal = true">Add Applicant</button>
        </header>

        <div>
          <ul class="list">
            <li class="applicant" v-for="applicant in position.applicants" :key="applicant.id">
              <p>{{ applicant.client.name }} - Skills: {{ applicant.client.skills }}</p>

              <div class="actions">
                <a href="#" @click="markAsWinner(applicant.id)">Marcar Ganador</a>
                <a href="#" @click="deleteApplicant(applicant.id)">Eliminar</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <modal :show="showModal" @close="showModal = false">
    <template #header>
      <h3>Clients</h3>
    </template>
    <template #body>
      <ul class="list">
        <li v-for="client in clients.data" :key="client.id">
          <p>
            <a href="#" @click="saveApplicant(client.id)">
              {{ client.name }} - {{ client.skills }}
            </a>
          </p>

          <span>{{ client.education_level }} </span>
        </li>
      </ul>
    </template>
  </modal>
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
  .create-new {
    width: 40%;
    padding: 20px;
    border: 1px solid #ccc6;
    border-radius: 5px;

    .subtitle {
      margin-bottom: 20px;
      display: block;
    }
  }

  .positions-view {
    width: 100%;
    margin-bottom: 20px;

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

  .applicants-view {
    width: 80%;
    padding: 20px;
    border: 1px solid #ccc6;
    border-radius: 5px;
    margin-right: 20px;

    h2 {
      margin: 0;
    }

    header {
      display: flex;
      justify-content: space-between;
    }

    .applicant {
      display: flex;
      justify-content: space-between;
      align-items: center;

      .actions {
        display: flex;
        font-size: 0.8rem;

        a {
          padding: 2px 5px;
          margin-right: 10px;
        }
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
