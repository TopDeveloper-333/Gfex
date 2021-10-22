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
                    <label class="btn btn-primary gf-button" v-on:click="onNextPage">Create</label>
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
                    <label class="btn btn-primary gf-button" v-on:click="onGoPage">Open</label>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>

    <div id="issueModal" class="gf-modal">
      <div class="gf-modal-content">
        <div class="gf-modal-header">
          <span class="gf-comment" style="margin-left:30px;color:white">Fastplan* platform</span>
          <span class="gf-close">&times;</span>
        </div>
        <p style="font-size: 1.25rem;margin-top:20px">Please input new project's name</p>
        <div style="margin-bottom:16px;margin-top:16px">
          <label class="btn btn-primary gf-button" v-on:click="onOK">OK</label>
        </div>
      </div>
    </div>

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
    ...mapState({
      projectName : state => state.project.projectName
    }),
  },

  methods: {
    onOK: function(event) {
      var modal = document.getElementById("issueModal");
      modal.style.display = "none";
    },
    onNextPage: async function(event) {
      if (this.myProjectName=="")
      {
        var modal = document.getElementById("issueModal");
        modal.style.display = "block";
        return; 
      }

      await store.dispatch('project/saveProjectName', this.myProjectName)
      this.$router.replace({ name: 'create' })
    },
    onGoPage: async function(event) {

    }
  },
  mounted() {

    this.myProjectName = this.projectName
    mountErrorDialog();
  }
}

function mountErrorDialog() {

  // Get the modal
  var modal = document.getElementById("issueModal");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("gf-close")[0];

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }      
}

</script>
