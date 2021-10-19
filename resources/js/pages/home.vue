<template>
  <div class="row">
    <div class="col-lg-10 m-auto">
    <form>
      <div class="card mb-3" style="max-width:800px">
        <div class="card-header gf-header">
          XXX Model
        </div>
        <div class="row g-0">
          <div class="col-md-4" style="display:flex; justify-content:center;">
            <img src="/assets/image/LOGO_GFEX.png" class="img-fluid rounded-start" style="opacity:0.6;max-width:200px">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h3 class="card-title">Project Type</h3>
              <p class="card-text">FDP or MONITORING PROJECT (Economics ONLY with FDP) <br>
              DRY GAS or GAS CONDENSATE PROJECT</p>

              <div style="display:flex;align-items:center;margin-bottom:6px">
                <label>Option IHIST <br> 
                  <small class="text-muted">(ON: FDP, OFF: MONITORING)</small>
                </label>
                <label class="switch" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bFDP">
                  <span class="slider round"></span>
                </label>
              </div>

              <div style="display:flex;align-items:center;margin-bottom:6px">
                <label class="col-9"> Condensate or Dry Gas <br> 
                  <small class="text-muted">(ON: Condensate, OFF: Dry Gas)</small>
                </label>
                <label class="switch col-3" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bCondensate">
                  <span class="slider round"></span>
                </label>
              </div>

              <div style="display:flex;align-items:center;margin-bottom:6px" v-show="bFDP">
                <label class="col-9">Echonomics Analysis <br> 
                  <small class="text-muted">(ON: Yes, OFF: No)</small>
                </label>
                <label class="switch col-3" style="margin: 0 0 auto auto">
                  <input type="checkbox" v-model="bEconomics">
                  <span class="slider round"></span>
                </label>
              </div>

              <div>
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
    return { title: this.$t('home') }
  },

  data() {
    return {
      bFDP : true,
      bCondensate: true,
      bEconomics : true
    }
  },

  computed: {
    ...mapState({
      isFDP: state => state.project.isFDP,
      isCondensate: state => state.project.isCondensate,
      isEconomics: state => state.project.isEconomics
    }),
  },

  methods: {
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
