<template>
  <card :title="$t('User Management')">
    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">    
      <p class="gf-item">Edit User</p>
      
      <!-- Name -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('name') }}</label>
        <div class="col-md-7">
          <input v-model="userInfo.name" class="form-control" type="text" name="name">
        </div>
      </div>

      <!-- Email -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('email') }}</label>
        <div class="col-md-7">
          <input v-model="userInfo.email" class="form-control" type="email" name="email">
        </div>
      </div>

      <!-- User Role -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('User Role') }}</label>
        <div class="col-md-7">
          <select class="form-select" name="role" v-model="userInfo.role">
            <option selected value="0">Permanent</option>
            <option value="1">Weekly-based</option>
          </select>
          <has-error :form="form" field="role" />
        </div>
      </div>

      <!-- From To -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('From') }}</label>
        <div class="col-md-3">
          <input v-model="userInfo.from" class="form-control" type="date" name="from">
          <has-error :form="form" field="from" />
        </div>
        <label class="col-md-1 col-form-label text-md-end">{{ $t('To') }}</label>
        <div class="col-md-3">
          <input v-model="userInfo.to" class="form-control" type="date" name="to">
          <has-error :form="form" field="from" />
        </div>
      </div>

      <!-- Revoke -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('User status') }}</label>
        <div class="col-md-7">
          <select class="form-select" name="status" v-model="userInfo.is_revoke">
            <option selected value="0">Available</option>
            <option value="1">Revoked</option>
          </select>
          <has-error :form="form" field="status" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="mb-3 row">
        <div class="col-md-9 ms-md-auto">
          <label class="btn btn-primary" v-on:click="onUpdate">Update User</label>
          <label class="btn btn-primary" v-on:click="onBack">Back</label>
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
      userInfo: null,
    }
  },
  computed:{
    ...mapState({
      users : state => state.auth.users,
    }),
  },

  async mounted () {
    this.userInfo = this.$route.params
    // alert(JSON.stringify(this.userInfo))
  },

  methods: {
    onUpdate() {
      this.$router.go(-1)
    },
    onBack() {
      this.$router.go(-1)
    }
  }
}

</script>
