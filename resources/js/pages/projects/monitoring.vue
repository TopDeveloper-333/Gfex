<template>
  <div class="row">
    <div class="m-auto">
    <form>
      <div class="card mb-3">
        <div class="card-header gf-header">
          FastPlan
        </div>
        <div class="row g-0">
          <div class="">
            <div class="card-body">
              <h3 class="card-title gf-title">Field X Project</h3>

              <div>
                <label class="btn btn-primary btn-simple active gf-button" v-on:click="onPrevPage">Previous</label>
                <label class="btn btn-primary btn-simple active gf-button" style="float:right" v-on:click="onNextPage">Next</label>
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
