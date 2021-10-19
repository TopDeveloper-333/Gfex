<template>
  <div class="row">
    <div class="col-lg-10 m-auto">
    <form>
      <div class="card mb-3" style="max-width:800px">
        <div class="card-header gf-header">
          XXX Model
        </div>
        <div class="row g-0">
          <div class="col-md-8">
            <div class="card-body">
              <h3 class="card-title">Monitoring Calculation</h3>

              <div>
                <label class="btn btn-primary btn-simple active" v-on:click="onPrevPage">Previous</label>
                <label class="btn btn-primary btn-simple active" v-on:click="onNextPage">Next</label>
              </div>

            </div>
          </div>
        </div>
      </div>
    </form>

    </div>
  </div>
</template>

<script>
import store from '~/store'

// import axios from 'axios'
export default {
  middleware: 'auth',

  // async asyncData () {
  //   const { data: projects } = await axios.get('/api/projects')

  //   return {
  //     projects
  //   }
  // },

  metaInfo () {
    return { title: this.$t('FDP Calculation') }
  },

  data() {
    return {
    }
  },

  methods: {
    onPrevPage: function(event) {
      this.$router.go(-1)
    },
    onNextPage: function(event) {
      
    }
  }

}
</script>
