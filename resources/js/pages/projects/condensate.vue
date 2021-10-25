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
          <div class="col-md-3" style="display:flex; justify-content:center;">
            <img src="/assets/image/LOGO_GFEX.png" class="img-fluid rounded-start" style="opacity:0.6;max-width:250px;max-height:300px">
          </div>
          <div class="col-md-9">
            <div class="card-body">
              <h3 class="card-title gf-title"><{{projectName}}> Field Project</h3>

              <condensate-pvt v-show="screenType==='PVT_SCREEN'">
              </condensate-pvt>
              <div v-show="screenType==='SURFACE_SCREEN'">
                Surface screen
              </div>
              <div v-show="screenType==='RESERVOIR_SCREEN'">
                Reservoir screen
              </div>
              <div v-show="screenType==='WELLHISTORY_SCREEN'">
                Well History screen
              </div>
              <div v-show="screenType==='ECONOMICS_SCREEN'">
                Economics screen
              </div>

              <div class="d-flex justify-content-between">
                <label class="btn btn-primary gf-button " v-on:click="onPrevPage">Previous</label>

                <!-- <div style="text-align:center" class="btn-group" role="group"> -->
                <div style="text-align:center">
                  <label class="btn gf-button" v-on:click="onPVTPage" v-bind:class="pvtButtonClass">PVT</label>
                  <label class="btn gf-button" v-on:click="onSurfacePage" v-bind:class="surfaceButtonClass">Surface</label>
                  <label class="btn gf-button" v-on:click="onReservoirPage" v-bind:class="reservoirButtonClass" v-show="isFDP=='1'">Reservoir</label>
                  <label class="btn gf-button" v-on:click="onWellHistoryPage" v-bind:class="wellHistoryButtonClass" v-show="isEconomics != true">Well History</label>
                  <label class="btn gf-button" v-on:click="onEconomicsPage" v-bind:class="economicsButtonClass" v-show="isEconomics == true && isFDP =='1'">Economics</label>
                </div>

                <div>
                  <label class="btn btn-outline-primary gf-button disabled" v-on:click="onNextPage">Execute</label>
                  <label class="btn btn-primary gf-button " v-on:click="onExitPage">Exit</label>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </form>

    </div>

    <div id="exitModal" class="gf-modal">
      <div class="gf-modal-content">
        <div class="gf-modal-header">
          <span class="gf-comment" style="margin-left:30px;color:white">Fastplan* platform</span>
          <span class="gf-close">&times;</span>
        </div>
        <p class="gf-comment" style="margin-top:6px !important; margin-bottom:6px !important;"><{{projectName}}> Field Project</p>
        <span style="font-size: 1.25rem">Do you want to exit this project?</span>
        <div style="margin-bottom:16px;margin-top:16px">
          <label class="btn btn-primary gf-button" v-on:click="onYes">Yes</label>
          <label class="btn btn-primary gf-button" v-on:click="onNo">No</label>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import store from '~/store'
import { mapState } from 'vuex'
import CondensatePvt from '~/components/CondensatePvt';

const PVT_SCREEN = "PVT_SCREEN"
const SURFACE_SCREEN = "SURFACE_SCREEN"
const RESERVOIR_SCREEN = "RESERVOIR_SCREEN"
const ECONOMICS_SCREEN = "ECONOMICS_SCREEN"
const WELLHISTORY_SCREEN = "WELLHISTORY_SCREEN"

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
    return { title: this.$t('GAS Condensate') }
  },

  components: {
    CondensatePvt
  },

  data() {
    return {
      screenType : PVT_SCREEN
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      isEconomics : state => state.project.isEconomics,
      isFDP: state => state.project.isFDP,
    }),
    pvtButtonClass: function() {
      if (this.screenType === PVT_SCREEN) return {'btn-primary': true}
      else return {'btn-outline-primary': true}
    },
    surfaceButtonClass: function () {
      if (this.screenType === SURFACE_SCREEN) return {'btn-primary': true}
      else return {'btn-outline-primary': true}
    },
    reservoirButtonClass: function () {
      if (this.screenType === RESERVOIR_SCREEN) return {'btn-primary': true}
      else return {'btn-outline-primary': true}
    },
    wellHistoryButtonClass: function () {
      if (this.screenType === WELLHISTORY_SCREEN) return {'btn-primary': true}
      else return {'btn-outline-primary': true}
    },
    economicsButtonClass: function () {
      if (this.screenType === ECONOMICS_SCREEN) return {'btn-primary': true}
      else return {'btn-outline-primary': true}
    },
  },

  methods: {
    onYes: function(event) {
      // hide exit dialog
      var modal = document.getElementById("exitModal");
      modal.style.display = "none";

      // go to home vue
      this.$router.replace('home')
    },
    onNo: function(event) {
      // hide exit dialog
      var modal = document.getElementById("exitModal");
      modal.style.display = "none";
    },
    onPVTPage: function(event) {
      this.screenType = PVT_SCREEN
    },
    onSurfacePage: function(event) {
      this.screenType = SURFACE_SCREEN
    },
    onReservoirPage: function(event) {
      this.screenType = RESERVOIR_SCREEN
    },
    onWellHistoryPage: function(event) {
      this.screenType = WELLHISTORY_SCREEN
    },
    onEconomicsPage: function(event) {
      this.screenType = ECONOMICS_SCREEN
    },
    onExitPage: function(event) {
      var modal = document.getElementById("exitModal");
      modal.style.display = "block";
    },
    onPrevPage: function(event) {
      this.$router.replace('create')
    },
    onNextPage: function(event) {

    }
  },
  mounted() {
    mountExitDialog();
  }

}

function mountExitDialog() {

  // Get the modal
  var modal = document.getElementById("exitModal");

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
