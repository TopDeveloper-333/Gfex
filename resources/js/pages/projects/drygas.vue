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
              <h3 class="card-title gf-title"><{{projectName}}> Field Project
              </h3>
              <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Dry Gas PVT Data</u></p>
              

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <p class="gf-item">Standard Conditions</p>
                <div id="standardCondition"></div>
              </div>

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <p class="gf-item">Gas Properties</p>
                <div id="gasPVT"></div>
              </div>

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <p class="gf-item">Rock Properties</p>
                <div id="rockProperties"></div>
              </div>

              <div class="d-flex justify-content-between">
                <label class="btn btn-primary gf-button " v-on:click="onPrevPage">Previous</label>

                <!-- <div style="text-align:center" class="btn-group" role="group"> -->
                <div style="text-align:center">
                  <label class="btn btn-primary gf-button-done" v-on:click="onPVTPage">PVT</label>
                  <label class="btn btn-primary gf-button" v-on:click="onSurfacePage">Surface</label>
                  <label class="btn btn-primary gf-button" v-on:click="onReservoirPage" v-show="isFDP=='1'">Reservoir</label>
                  <label class="btn btn-primary gf-button" v-on:click="onWellHistoryPage" v-show="isEconomics != true">Well History</label>
                  <label class="btn btn-primary gf-button" v-on:click="onEconomicsPage" v-show="isEconomics == true && isFDP =='1'">Economics</label>
                </div>

                <div>
                  <label class="btn btn-primary gf-button " v-on:click="onNextPage">Execute</label>
                  <label class="btn btn-primary gf-button " v-on:click="onExitPage">Exit</label>
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
    return { title: this.$t('Dry GAS') }
  },

  data() {
    return {
      standardCondition: null,
      gasPVT : null,
      rockProperties: null,
      myDryGas: {}
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      drygas : state => state.project.drygas,
      isEconomics : state => state.project.isEconomics,
      isFDP: state => state.project.isFDP,
    }),
  },

  methods: {
    onPVTPage: function(event) {

    },
    onSurfacePage: function(event) {

    },
    onReservoirPage: function(event) {

    },
    onWellHistoryPage: function(event) {
      
    },
    onEconomicsPage: function(event) {

    },
    onExitPage: function(event) {

    },
    onPrevPage: function(event) {
      this.$router.go(-1)
    },
    onNextPage: async function(event) {
      this.myDryGas = {
        standardConditions: {
          Psc: 0, Tsc : 0
        },
        gasProperties: {
          gasCompressibility: "", gasViscosity:0, specificGravity: 0, resTemp: 0, N2:0, CO2:0, H2S:0
        },
        rockProperties: {
          conateWaterSaturation:0, waterCompressibility: "", rockCompressibility: ""
        }
      }

      this.myDryGas.standardConditions.Psc = this.standardCondition.getValue('A1');
      this.myDryGas.standardConditions.Tsc = this.standardCondition.getValue('B1');
      this.myDryGas.gasProperties.gasCompressibility = this.gasPVT.getValue('A1');
      this.myDryGas.gasProperties.gasViscosity = this.gasPVT.getValue('B1');
      this.myDryGas.gasProperties.specificGravity = this.gasPVT.getValue('C1');
      this.myDryGas.gasProperties.resTemp = this.gasPVT.getValue('D1');
      this.myDryGas.gasProperties.N2 = this.gasPVT.getValue('E1');
      this.myDryGas.gasProperties.CO2 = this.gasPVT.getValue('F1');
      this.myDryGas.gasProperties.H2S = this.gasPVT.getValue('G1');
      this.myDryGas.rockProperties.conateWaterSaturation = this.rockProperties.getValue('A1');
      this.myDryGas.rockProperties.waterCompressibility = this.rockProperties.getValue('B1');
      this.myDryGas.rockProperties.rockCompressibility = this.rockProperties.getValue('C1');

      await store.dispatch('project/saveDryGas', this.myDryGas)
    }
  },
  mounted() {

    this.myDryGas = this.drygas

    // Standard Conditions
    var standardConditionsData = [
        [14.7, 60],
    ];
    
    this.standardCondition = jspreadsheet(document.getElementById('standardCondition'), {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        data:standardConditionsData,
        columns: [
            {
                type: 'numeric',
                title:'Psc (psia)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Tsc (Deg F)',
                width: 120,
                decimal:','
            },
        ]
    });
    this.standardCondition.hideIndex();

    // GAS PVT
    var gasPVTData = [
      ["35.D-05", 0.025, 0.6, 300, 0.03, 0.06, 0.02]
    ];

    this.gasPVT = jspreadsheet(document.getElementById('gasPVT'), {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        data:gasPVTData,
        columns: [
            {
                type: 'text',
                title:'GAS COMPRESSIBILITY (1/psi)',
                width: 240,
                decimal:','
            },
            {
                type: 'numeric',
                title:'GAS VISCOSITY (cp)',
                width: 180,
                decimal:','
            },
            {
                type: 'numeric',
                title:'SPECIFIC GRAVITY',
                width: 160,
                decimal:','
            },
            {
                type: 'numeric',
                title:'RES. TEMP. (Deg F)',
                width: 160,
                decimal:','
            },
            {
                type: 'numeric',
                title:'N2 (decimal)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'CO2 (decimal)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'H2S (decimal)',
                width: 140,
                decimal:','
            },
        ]
    });
    this.gasPVT.hideIndex();

    // ROCK PROPERTIES
    var rockPropertiesData = [
        [0.30, "3.D-06", "3.D-06"],
    ];
    
    this.rockProperties = jspreadsheet(document.getElementById('rockProperties'), {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        data:rockPropertiesData,
        columns: [
            {
                type: 'numeric',
                title:'Conate Water Saturation',
                width: 240,
                decimal:','
            },
            {
                type: 'text',
                title:'Water Compressibility (1/psi)',
                width: 240,
            },
            {
                type: 'numeric',
                title:'Rock Compressibility (1/psi)',
                width: 240,
            },
        ]
    });
    this.rockProperties.hideIndex();

  }

}
</script>
