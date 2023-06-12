<script>
import { RouterLink, RouterView } from 'vue-router'
import { mapState, mapGetters, mapActions } from 'vuex'
//import axios from 'axios'

export default {
  components: {
    RouterLink,
    RouterView
  },

  computed: {
    ...mapState('settings', {
      user: (state) => state.user
    }),

    ...mapGetters('settings', {
      token: 'token'
    })
  },

  async mounted() {
    await this.getSettings()
  },

  methods: {
    ...mapActions('settings', {
      getSettings: 'getSettings'
    })
  }
}
</script>

<template>
  <div></div>
  <header>
    <div class="wrapper">
      <h3>NGO</h3>

      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/companies">Companies</RouterLink>
        <RouterLink to="/positions">Positions</RouterLink>
        <RouterLink to="/people">People</RouterLink>
      </nav>
    </div>
  </header>

  <div id="main-view">
    <RouterView />
  </div>
</template>

<style lang="scss" scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
  max-height: 250px;
  width: 140px;

  h3 {
    font-weight: 500;
    font-size: 2.2rem;
    top: -10px;
    margin: 0;
    margin-bottom: 20px;
  }
  h4 {
    font-weight: 500;
    font-size: 1.4rem;
    top: -10px;
    margin: 0;
  }
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  display: flex;
  flex-direction: column;
}

#main-view {
  width: 960px;
  padding-left: 45px;
}

nav a.router-link-exact-active {
  color: #65c556;
  font-weight: bold;
  border-left: 1px solid #65c556;
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  padding: 0 1rem;
  margin-bottom: 20px;
}

@media (min-width: 1024px) {
  header {
    display: flex;
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  nav {
    text-align: left;
    font-size: 1rem;
    padding: 1rem 0;
  }
}
</style>
