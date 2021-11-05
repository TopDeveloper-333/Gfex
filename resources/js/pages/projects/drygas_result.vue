<template>
  <div class="row">
    <div class="m-auto">
    <form>
      <loading :active.sync="isLoading" 
             :is-full-page="fullPage"></loading>
      <div class="card mb-3">
        <div class="card-header gf-header">
          <img src="/assets/image/LOGO_GFEX.png" style="max-width:150px;max-height:180px;margin-left:-7px;float:left">
          FastPlan* Gas & Gas Condensate<br>
          <p style="font-size:3rem !important">Conventional and Shale Reservoirs</p>
        </div>
        <div class="row g-0" style="background-color:#fdf500;">
          <div class="col-md-10 offset-md-1">
            <div class="card-body">
              <h3 class="card-title gf-title"><{{projectName}}> Field Project
              </h3>
              <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Result</u></p>

              <div>
                <label class="btn btn-primary gf-button" style="float:right;margin-left:10px" v-on:click="onPlot">{{plotLabel}}</label>
              </div>

              <div style="display:flex;margin-bottom:6px;text-align:left" class="row" v-show="bShowPlot == false">
                <p class="gf-item">Output Files</p>
                <multiselect v-model="outputFile" @input="onChange" :options="outputFileOptions" track-by="name" label="name" placeholder="Select OUTPUT files"></multiselect>
              </div>

              <div style="display:flex;margin-bottom:6px;margin-left:12px;text-align:left" class="row" v-show="bShowPlot == false">
                <textarea v-model="dataContent" ></textarea>
              </div>

              <div style="height:50px" v-show="bShowPlot == true">
              </div>

              <hr class="gf-line" v-show="bShowPlot == true">

              <!-- <graph v-show="bShowPlot == true" 
                     v-bind:options="options"
                     v-bind:data="resRawDryGas"
                     v-bind:labels="labels"></graph> -->

              <div class="d-flex justify-content-between">
                <label class="btn btn-primary gf-button" v-on:click="onPrevPage">Previous</label>
                <div>
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
          <span class="gf-comment" style="margin-left:30px;color:white">FastPlan* Gas & Gas Condensate</span>
          <span class="gf-close" id="gf-exit-cancel">&times;</span>
        </div>
        <p class="gf-comment" style="margin-top:6px !important; margin-bottom:6px !important;"><{{projectName}}> Field Project</p>
        <span style="font-size: 1.25rem" v-show="isSaveAs==false">Do you want to save this project?</span>
        <div v-show="isSaveAs==true">
          <span style="font-size: 1.25rem">Project Name: </span>
          <input style="font-size: 1.25rem" maxlength="20" v-model="newProjectName" placeholder="Please input new project name">
        </div>
        <div style="margin-bottom:16px;margin-top:16px">
          <label class="btn btn-primary gf-button" v-on:click="onSaveAs" v-show="hideSaveAsButton==false">Save As</label>
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
import Multiselect from 'vue-multiselect'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import html2canvas from 'html2canvas';
import Graph from '~/components/Graph.vue';


// import axios from 'axios'
export default {
  name: 'DrygasResult',
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('Drygas Result') }
  },

  components: {
    Multiselect,
    Loading,
    Graph
  },

  data() {
    return {
      isLoading: false,
      fullPage: true,
      isSaveAs : false,
      hideSaveAsButton: false,
      newProjectName: "",
      plotLabel: "Plot",
      bShowPlot : false,
      outputFile: null,
      outputFileOptions: [
        { name: 'PLOT_OF'},
        { name: 'ECONOMICS'},
        { name: 'RESULT_OF'},
      ],
      dataContent: ''
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      resRawDryGas : state => state.project.resRawDryGas
    }),
  },

  methods: {
    onChange: function(event) {
      debugger
      if (this.outputFile != null && this.outputFile.name == 'PLOT_OF')
        this.dataContent = this.resRawDryGas.PLOT_OF
      else if (this.outputFile != null && this.outputFile.name == 'ECONOMICS')
        this.dataContent = this.resRawDryGas.ECONOMICS
      else if (this.outputFile != null && this.outputFile.name == 'RESULT_OF')
        this.dataContent = this.resRawDryGas.RESULT_OF
    },
    onPlot: function(event) {
      if(this.bShowPlot == false) {
        this.bShowPlot = true
        this.plotLabel = "Data"
      }
      else {
        this.bShowPlot = false
        this.plotLabel = "Plot"
      }
    },
    onSaveAs: function(event) {
      this.isSaveAs = true
      this.hideSaveAsButton = true
    },
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

      // go to home vue
      this.$router.replace('home')
    },
    onExitPage: function (event) {
      this.isSaveAs = false
      this.hideSaveAsButton = false

      var modal = document.getElementById("exitModal");
      modal.style.display = "block";
    },
    onPrevPage: function(event) {
      this.$router.replace('drygas')
    },
  },
  mounted() {
    
    mountExitDialog();
  }

}

function mountExitDialog() {

  // Get the modal
  var modal = document.getElementById("exitModal");

  // Get the <span> element that closes the modal
  var span = document.getElementById("gf-exit-cancel");

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

<style scoped>
.jexcel > thead > tr > td {
  font-size: 20px;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
.multiselect {
  max-width: 370px;
  /* margin: auto; */
}
textarea{
    width: 90%;
    height: 450px;
    white-space: nowrap;
    overflow-x: auto;
    resize: none;
}
</style>
