<template>
  <div class="row">
    <div class="col-lg-7 m-auto">
      <div class="card">
        <form @submit.prevent="send" @keydown="form.onKeydown($event)">
          <alert-success :form="form" :message="status" />

          <div class="card-header gf-header">
            Reset Password
          </div>

          <!-- Email -->
          <div class="mb-3 row" style="margin-top:32px">
            <label class="col-md-3 col-form-label text-md-end gf-control">{{ $t('email') }}</label>
            <div class="col-md-7">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" 
                class="form-control gf-control" type="email" name="email">
              <has-error :form="form" field="email" />
            </div>
          </div>

          <!-- Submit Button -->
          <div class="mb-3 row">
            <div class="col-md-9 ms-md-auto">
              <v-button class="gf-button" :loading="form.busy">
                {{ $t('send_password_reset_link') }}
              </v-button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')

      this.status = data.status

      this.form.reset()
    }
  }
}
</script>
