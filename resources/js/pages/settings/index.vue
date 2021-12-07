<template>
  <div class="row justify-content-center">
    <div class="col-md-2 mb-3">
      <card :title="$t('settings')" class="settings-card">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" :key="tab.route" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              <fa :icon="tab.icon" fixed-width />
              {{ tab.name }}
            </router-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="col-md-10">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
</template>

<script>
import store from '~/store'
import { mapState } from 'vuex'

export default {
  middleware: ['auth', 'theme'],

  computed: {
    ...mapState({
      user : state => state.auth.user,
    }),
    tabs () {
      let tt = [
        {
          icon: 'user',
          name: this.$t('profile'),
          route: 'settings.profile'
        },
        {
          icon: 'lock',
          name: this.$t('password'),
          route: 'settings.password'
        },
        {
          icon: 'palette',
          name: this.$t('Theme'),
          route: 'settings.theme'
        },
        {
          icon: 'book',
          name: this.$t('Project'),
          route: 'settings.removeproject'
        },
      ]

      if (this.user != undefined && this.user.is_admin == 1) {
        tt.push({
          icon: 'users',
          name: this.$t('Users'),
          route: 'settings.users'
        })
        tt.push({
          icon: 'money-check',
          name: this.$t('License'),
          route: 'settings.license'
        })
      }

      return tt
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
