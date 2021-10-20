<template>
  <div class="row">
    <div class="m-auto">
    <form>
      <div class="card mb-3">
        <div class="card-header gf-header">
          FastPlan* Platform<br>
          <p style="font-size:3rem !important">Conventional and Shale Reservoirs</p>
        </div>
        <div class="row g-0" style="background-color:#fdf500;">
          <div class="col-md-4" style="display:flex; justify-content:center;">
            <img src="/assets/image/LOGO_GFEX.png" class="img-fluid rounded-start" style="opacity:0.6;max-width:250px;max-height:300px">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h3 class="card-title gf-title text-wrap" >Project Management</h3>
              <p class="card-text gf-comment">Create new and View past</p>
              <hr class="gf-line">

              <div class="row">
                <div class="col-5">
                  <label class="gf-item">New Project
                  </label>

                  <div style="display:flex;align-items:center;margin-bottom:16px;margin-top:16px">
                    <input class="form-control gf-control" maxlength="20" v-model="myProjectName" placeholder="Non Empty">                    
                  </div>

                  <div>
                    <label class="btn btn-primary btn-simple active gf-button" v-on:click="onNextPage">Create</label>
                  </div>

                </div>
                <div class="col-2"></div>
                <div class="col-5">
                  <label class="gf-item">Past Projects
                  </label>

                  <div style="display:flex;align-items:center;margin-bottom:16px;margin-top:16px">
                    <select class="form-select gf-control" aria-label="Default select example">
                      <option selected value="US Test">US Test</option>
                      <option value="Russia Test">Russia Test </option>
                    </select>
                  </div>

                  <div>
                    <label class="btn btn-primary btn-simple active gf-button" v-on:click="onGoPage">Open</label>
                  </div>

                </div>
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
    return { title: this.$t('Project Management') }
  },

  data() {
    return {
      myProjectName : ""
    }
  },

  computed: {
  },

  methods: {
    onNextPage: async function(event) {
      if (this.myProjectName=="")
        return;

      await store.dispatch('project/saveProjectName', this.myProjectName)
      this.$router.push({ name: 'create' })
    },
    onGoPage: async function(event) {

    }
  },
}
</script>
