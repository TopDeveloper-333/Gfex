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

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">User List</p>
      <div id="usersListSheet"></div>
    </div>

    <div class="mb-3 row">
      <div class="col-md-12 ms-md-auto">
        <v-button type="success">
          {{ $t('Update users') }}
        </v-button>
      </div>
    </div>

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
      }),
      usersListSheet: null
    }
  },
  computed:{
    ...mapState({
      users : state => state.auth.users,
    }),
  },

  async mounted () {
    await store.dispatch('auth/fetchUsers', 0)

    document.getElementById('usersListSheet').innerHTML = ''
    this.usersListSheet = jspreadsheet(document.getElementById('usersListSheet'), {
        data:this.users,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        search: true,
        pagination: 10,
        columns: [
            {
                type: 'text',
                title:'Id',
                width: 50,
                decimal:','
            },
            {
                type: 'text',
                title:'Name',
                width: 230,
                decimal:','
            },
            {
                type: 'text',
                title:'Email',
                width: 230,
                decimal:','
            },
            {
                type: 'dropdown',
                title:'Role',
                width: 180,
                source: ['Permanent', 'Temporary']
            },
            {
                type: 'calendar',
                title:'From',
                width: 160,
                decimal:','
            },
            {
                type: 'calendar',
                title:'To',
                width: 160,
                decimal:','
            },
            {
                type: 'dropdown',
                title:'Revoke',
                width: 130,
                source: ['Yes', 'No']
            },
        ],
    });
    this.usersListSheet.hideIndex();

  },

  methods: {
    async addNewUser () {
      
    }
  }
}
</script>

<style scoped>
.jexcel > thead > tr > td {
  font-size: 14px;
  background-color: white !important;
  border-top: 0px !important;
  color: black;
}

.jexcel > tbody > tr > td {
  font-size: 14px;
  background-color: white;
  border-top: 0px !important;
  color: black;
}
.jexcel .highlight-selected {
  background-color: white !important;
  color: black !important;
}
</style>
