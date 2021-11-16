<template>
  <card :title="$t('Users Management')">
    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">

    <form @submit.prevent="addNewUser" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('info_updated')" />

      <p class="gf-item">Add new user</p>
      
      <!-- Name -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('name') }}</label>
        <div class="col-md-7">
          <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" type="text" name="name">
          <has-error :form="form" field="name" />
        </div>
      </div>

      <!-- Email -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('email') }}</label>
        <div class="col-md-7">
          <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
          <has-error :form="form" field="email" />
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end ">{{ $t('password') }}</label>
        <div class="col-md-7">
          <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" 
            class="form-control" type="password" name="password">
          <has-error :form="form" field="password" />
        </div>
      </div>

      <!-- Password Confirmation -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('confirm_password') }}</label>
        <div class="col-md-7">
          <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" 
            class="form-control" type="password" name="password_confirmation">
          <has-error :form="form" field="password_confirmation" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="mb-3 row">
        <div class="col-md-9 ms-md-auto">
          <v-button :loading="form.busy" type="success">
            {{ $t('Add new user') }}
          </v-button>
        </div>
      </div>
    </form>

    <p class="gf-item">User List</p>

    </div>
  </card>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import Form from 'vform'
import store from '~/store'
import { mapState } from 'vuex'
import Multiselect from 'vue-multiselect'

export default {
  scrollToTop: false,

  middleware: 'auth',

  components: {
    Multiselect
  },

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data() {
    return {
      form: new Form({
        name:'',
        email: '',
        password: '',
        password_confirmation: ''
      })
    }
  },
  computed:{
    ...mapState({
      users : state => state.auth.users,
    }),
  },

  mounted () {
    store.dispatch('auth/fetchUsers', 0)
  },

  methods: {
    async addNewUser () {
      
    }
  }
}
</script>
