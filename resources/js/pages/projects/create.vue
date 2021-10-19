<template>
  <div class="row">
    <div class="m-auto">
    <form>
      <div class="card mb-3">
        <div class="card-header gf-header" style="text-align:center; font-size:2.5rem">
          FastPlan
        </div>
        <div class="row g-0">
          <div class="col-md-4" style="display:flex; justify-content:center;">
            <img src="/assets/image/LOGO_GFEX.png" class="img-fluid rounded-start" style="opacity:0.6;max-width:250px;max-height:300px">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h3 class="card-title gf-title text-wrap" >Field <{{projectName}}> Project</h3>
              <p class="card-text gf-comment">FDP or FIELD <{{projectName}}> MONITORING PROJECT (Economics ONLY with FDP) <br>
              DRY GAS or GAS CONDENSATE PROJECT</p>
              <hr class="gf-line">

              <div style="display:flex;align-items:center;margin-bottom:6px">
                <label class="gf-item">FDP (Field Development Planning) 
                </label>
                <label class="switch" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bFDP" false-value=1 true-value=0>
                  <span class="slider round"></span>
                </label>
              </div>

              <div style="display:flex;align-items:center;margin-bottom:6px">
                <label class="gf-item">FIELD MONITORING
                </label>
                <label class="switch" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bFDP" false-value=0 true-value=1>
                  <span class="slider round"></span>
                </label>
              </div>

              <div style="display:flex;align-items:center;margin-bottom:6px">
                <label class="gf-item">DRY GAS
                </label>
                <label class="switch" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bCondensate" false-value=1 true-value=0>
                  <span class="slider round"></span>
                </label>
              </div>

              <div style="display:flex;align-items:center;margin-bottom:6px">
                <label class="gf-item">GAS CONDENSATE
                </label>
                <label class="switch" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bCondensate" false-value=0 true-value=1>
                  <span class="slider round"></span>
                </label>
              </div>

              <div style="display:flex;align-items:center;margin-bottom:6px" v-show="bFDP=='1'">
                <label class="gf-item">ECONOMICS ANAYLSIS
                </label>
                <label class="switch" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bEconomics">
                  <span class="slider round"></span>
                </label>
              </div>

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
import { mapState } from 'vuex'

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
    return { title: this.$t('create') }
  },

  data() {
    return {
      bFDP : "1",
      bCondensate: "1",
      bEconomics : "1"
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      isFDP: state => state.project.isFDP,
      isCondensate: state => state.project.isCondensate,
      isEconomics: state => state.project.isEconomics
    }),
  },

  methods: {
    onPrevPage: function(event) {
      this.$router.go(-1)
    },
    onNextPage: async function(event) {
      var payload = {}
      payload.isFDP = this.bFDP
      payload.isCondensate = this.bCondensate
      payload.isEconomics = this.bEconomics
      await store.dispatch('project/saveProjectType', payload)

      if (this.isFDP)
        this.$router.push({ name: 'fdp' })
      else
        this.$router.push({ name: 'monitoring'})
    }
  },

  mounted() {
    this.bFDP = this.isFDP
    this.bCondensate = this.isCondensate
    this.bEconomics = this.isEconomics
  }
}
</script>
