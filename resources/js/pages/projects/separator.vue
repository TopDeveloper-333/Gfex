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
          <div class="col-md-2" style="display:flex; justify-content:center;">
            <img src="/assets/image/LOGO_GFEX.png" class="img-fluid rounded-start" style="opacity:0.6;max-width:250px;max-height:300px">
          </div>
          <div class="col-md-10">
            <div class="card-body">
              <h3 class="card-title gf-title"><{{projectName}}> Field Project
              </h3>
              <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Stream PVT & Separator Data</u></p>
              

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <p class="gf-item">Original Stream Composition</p>
                <div id="originalStreamComposition1"></div>
                <div id="originalStreamComposition2"></div>
              </div>

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <p class="gf-item">Saturated Reservoir Conditions</p>
                <div id="saturatedReservoirConditions"></div>
              </div>

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <p class="gf-item">Separator Conditions <span>(Input 2 or 3 stages)</span></p>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 1</p>
                  <div id="setNumber1"></div>
                </div>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 2</p>
                  <div id="setNumber2"></div>
                </div>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 3</p>
                  <div id="setNumber3"></div>
                </div>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 4</p>
                  <div id="setNumber4"></div>
                </div>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 5</p>
                  <div id="setNumber5"></div>
                </div>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 6</p>
                  <div id="setNumber6"></div>
                </div>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 7</p>
                  <div id="setNumber7"></div>
                </div>
                <div class="col-lg-4 col-md-6" style="text-align:center">
                  <p class="gf-subitem">Set Number 8</p>
                  <div id="setNumber8"></div>
                </div>
                <div class="col-4" style="text-align:center">
                  <p class="gf-subitem">Set Number 9</p>
                  <div id="setNumber9"></div>
                </div>
              </div>

              <div class="d-flex justify-content-between">
                <label class="btn btn-primary gf-button" v-on:click="onPrevPage">Previous</label>
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
    return { title: this.$t('Separtor Processing / Optimizer') }
  },

  data() {
    return {
      originalStreamComposition1: null,
      originalStreamComposition2: null,
      saturatedReservoirConditions : null,
      separatorConditions: null,
      mySEP : {}
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      sep : state => state.project.sep
    }),
  },

  methods: {
    onExitPage: function (event) {

    },
    onPrevPage: function(event) {
      this.$router.go(-1)
    },
    onNextPage: async function(event) {
      this.mySEP = { 
        originalStreamComposition1: {
          N2:0, CO2:0, H2S:0, C1:0, C2:0, C3:0, iC4:0, nC4:0, iC5:0, nC5:0, C6:0, C7:0,
        },
        originalStreamComposition2: {
          MolecularWeight:0, SpecificGravity:0
        },
        saturatedReservoirConditions: {
          PSAT:0, TRES:0,
        },
        separatorConditions: {
          setNumber1: [ [0, 0], [0, 0]],
          setNumber2: [ [0, 0], [0, 0]],
          setNumber3: [ [0, 0], [0, 0]],
          setNumber4: [ [0, 0], [0, 0]],
          setNumber5: [ [0, 0], [0, 0]],
          setNumber6: [ [0, 0], [0, 0]],
          setNumber7: [ [0, 0], [0, 0]],
          setNumber8: [ [0, 0], [0, 0]],
          setNumber9: [ [0, 0], [0, 0]],
        }
      }
      this.mySEP.originalStreamComposition1.N2 = this.originalStreamComposition1.getValue('A1');
      this.mySEP.originalStreamComposition1.CO2 = this.originalStreamComposition1.getValue('B1');
      this.mySEP.originalStreamComposition1.H2S = this.originalStreamComposition1.getValue('C1');
      this.mySEP.originalStreamComposition1.C1 = this.originalStreamComposition1.getValue('D1');
      this.mySEP.originalStreamComposition1.C2 = this.originalStreamComposition1.getValue('E1');
      this.mySEP.originalStreamComposition1.C3 = this.originalStreamComposition1.getValue('F1');
      this.mySEP.originalStreamComposition1.iC4 = this.originalStreamComposition1.getValue('G1');
      this.mySEP.originalStreamComposition1.nC4 = this.originalStreamComposition1.getValue('H1');
      this.mySEP.originalStreamComposition1.iC5 = this.originalStreamComposition1.getValue('I1');
      this.mySEP.originalStreamComposition1.nC5 = this.originalStreamComposition1.getValue('J1');
      this.mySEP.originalStreamComposition1.C6 = this.originalStreamComposition1.getValue('K1');
      this.mySEP.originalStreamComposition1.C7 = this.originalStreamComposition1.getValue('L1');
      this.mySEP.originalStreamComposition2.MolecularWeight = this.originalStreamComposition2.getValue('A1');
      this.mySEP.originalStreamComposition2.SpecificGravity = this.originalStreamComposition2.getValue('B1');
      this.mySEP.saturatedReservoirConditions.PSAT = this.saturatedReservoirConditions.getValue('A1');
      this.mySEP.saturatedReservoirConditions.TRES = this.saturatedReservoirConditions.getValue('B1');

      this.mySEP.separatorConditions.setNumber1[0][0] = this.setNumber1.getValue('A1');
      this.mySEP.separatorConditions.setNumber1[0][1] = this.setNumber1.getValue('B1');
      this.mySEP.separatorConditions.setNumber1[1][0] = this.setNumber1.getValue('A2');
      this.mySEP.separatorConditions.setNumber1[1][1] = this.setNumber1.getValue('B2');
      this.mySEP.separatorConditions.setNumber1[2][0] = this.setNumber1.getValue('A3');
      this.mySEP.separatorConditions.setNumber1[2][1] = this.setNumber1.getValue('B3');

      this.mySEP.separatorConditions.setNumber2[0][0] = this.setNumber2.getValue('A1');
      this.mySEP.separatorConditions.setNumber2[0][1] = this.setNumber2.getValue('B1');
      this.mySEP.separatorConditions.setNumber2[1][0] = this.setNumber2.getValue('A2');
      this.mySEP.separatorConditions.setNumber2[1][1] = this.setNumber2.getValue('B2');
      this.mySEP.separatorConditions.setNumber2[2][0] = this.setNumber2.getValue('A3');
      this.mySEP.separatorConditions.setNumber2[2][1] = this.setNumber2.getValue('B3');

      this.mySEP.separatorConditions.setNumber3[0][0] = this.setNumber3.getValue('A1');
      this.mySEP.separatorConditions.setNumber3[0][1] = this.setNumber3.getValue('B1');
      this.mySEP.separatorConditions.setNumber3[1][0] = this.setNumber3.getValue('A2');
      this.mySEP.separatorConditions.setNumber3[1][1] = this.setNumber3.getValue('B2');
      this.mySEP.separatorConditions.setNumber3[2][0] = this.setNumber3.getValue('A3');
      this.mySEP.separatorConditions.setNumber3[2][1] = this.setNumber3.getValue('B3');

      this.mySEP.separatorConditions.setNumber4[0][0] = this.setNumber4.getValue('A1');
      this.mySEP.separatorConditions.setNumber4[0][1] = this.setNumber4.getValue('B1');
      this.mySEP.separatorConditions.setNumber4[1][0] = this.setNumber4.getValue('A2');
      this.mySEP.separatorConditions.setNumber4[1][1] = this.setNumber4.getValue('B2');
      this.mySEP.separatorConditions.setNumber4[2][0] = this.setNumber4.getValue('A3');
      this.mySEP.separatorConditions.setNumber4[2][1] = this.setNumber4.getValue('B3');

      this.mySEP.separatorConditions.setNumber5[0][0] = this.setNumber5.getValue('A1');
      this.mySEP.separatorConditions.setNumber5[0][1] = this.setNumber5.getValue('B1');
      this.mySEP.separatorConditions.setNumber5[1][0] = this.setNumber5.getValue('A2');
      this.mySEP.separatorConditions.setNumber5[1][1] = this.setNumber5.getValue('B2');
      this.mySEP.separatorConditions.setNumber5[2][0] = this.setNumber5.getValue('A3');
      this.mySEP.separatorConditions.setNumber5[2][1] = this.setNumber5.getValue('B3');

      this.mySEP.separatorConditions.setNumber6[0][0] = this.setNumber6.getValue('A1');
      this.mySEP.separatorConditions.setNumber6[0][1] = this.setNumber6.getValue('B1');
      this.mySEP.separatorConditions.setNumber6[1][0] = this.setNumber6.getValue('A2');
      this.mySEP.separatorConditions.setNumber6[1][1] = this.setNumber6.getValue('B2');
      this.mySEP.separatorConditions.setNumber6[2][0] = this.setNumber6.getValue('A3');
      this.mySEP.separatorConditions.setNumber6[2][1] = this.setNumber6.getValue('B3');

      this.mySEP.separatorConditions.setNumber7[0][0] = this.setNumber7.getValue('A1');
      this.mySEP.separatorConditions.setNumber7[0][1] = this.setNumber7.getValue('B1');
      this.mySEP.separatorConditions.setNumber7[1][0] = this.setNumber7.getValue('A2');
      this.mySEP.separatorConditions.setNumber7[1][1] = this.setNumber7.getValue('B2');
      this.mySEP.separatorConditions.setNumber7[2][0] = this.setNumber7.getValue('A3');
      this.mySEP.separatorConditions.setNumber7[2][1] = this.setNumber7.getValue('B3');

      this.mySEP.separatorConditions.setNumber8[0][0] = this.setNumber8.getValue('A1');
      this.mySEP.separatorConditions.setNumber8[0][1] = this.setNumber8.getValue('B1');
      this.mySEP.separatorConditions.setNumber8[1][0] = this.setNumber8.getValue('A2');
      this.mySEP.separatorConditions.setNumber8[1][1] = this.setNumber8.getValue('B2');
      this.mySEP.separatorConditions.setNumber8[2][0] = this.setNumber8.getValue('A3');
      this.mySEP.separatorConditions.setNumber7[2][1] = this.setNumber8.getValue('B3');

      this.mySEP.separatorConditions.setNumber9[0][0] = this.setNumber9.getValue('A1');
      this.mySEP.separatorConditions.setNumber9[0][1] = this.setNumber9.getValue('B1');
      this.mySEP.separatorConditions.setNumber9[1][0] = this.setNumber9.getValue('A2');
      this.mySEP.separatorConditions.setNumber9[1][1] = this.setNumber9.getValue('B2');
      this.mySEP.separatorConditions.setNumber9[2][0] = this.setNumber9.getValue('A3');
      this.mySEP.separatorConditions.setNumber9[2][1] = this.setNumber9.getValue('B3');

      await store.dispatch('project/saveSEP', this.mySEP)
    }
  },
  mounted() {

    this.mySEP = this.sep

    // Original Stream Composition
    var originalStreamComposition1Data = [
        [0.0198, 0.02313, 0.000, 0.26659, 0.12725, 0.1031, 0.01282, 0.05188, 0.01424, 0.02977, 0.04801, 0.30769],
    ];
    
    this.originalStreamComposition1 = jspreadsheet(document.getElementById('originalStreamComposition1'), {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        data:originalStreamComposition1Data,
        columns: [
            {
                type: 'numeric',
                title:'N2',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'CO2',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'H2S',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'C1',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'C2',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'C3',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'iC4',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'nC4',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'iC5',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'nC5',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'C6',
                width: 80,
                decimal:','
            },
            {
                type: 'numeric',
                title:'C7+',
                width: 80,
                decimal:','
            },
        ]
    });
    this.originalStreamComposition1.hideIndex();

    // 
    var originalStreamComposition2Data = [
        [220, 0.7990],
    ];
    
    this.originalStreamComposition2 = jspreadsheet(document.getElementById('originalStreamComposition2'), {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        data:originalStreamComposition2Data,
        columns: [
            {
                type: 'numeric',
                title:'Molecular Weight',
                width: 200,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Specific Gravity',
                width: 200,
            },
        ]
    });
    this.originalStreamComposition2.hideIndex();

    // Saturated Reservoir Conditions
    var saturatedReservoirConditionsData = [
      [2677, 244]
    ];

    this.saturatedReservoirConditions = jspreadsheet(document.getElementById('saturatedReservoirConditions'), {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        data:saturatedReservoirConditionsData,
        columns: [
            {
                type: 'numeric',
                title:'PSAT (psia)',
                width: 160,
                decimal:','
            },
            {
                type: 'numeric',
                title:'TRES (Deg F)',
                width: 160,
            },
        ]
    });
    this.saturatedReservoirConditions.hideIndex();

    // Saparator Conditions
    var setNumberProps = {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Pressure (psia)',
                width: 190,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Temperature (Deg F)',
                width: 190,
            },
        ]
    }
    
    var setNumber1Data = [
      [1000, 72],
      [14.7, 60.0],
      [,]
    ];
    setNumberProps.data = setNumber1Data;
    this.setNumber1 = jspreadsheet(document.getElementById('setNumber1'), setNumberProps);
    this.setNumber1.hideIndex();

    var setNumber2Data = [
      [800, 72],
      [14.7, 60.0],
      [,]
    ];
    setNumberProps.data = setNumber2Data;
    this.setNumber2 = jspreadsheet(document.getElementById('setNumber2'), setNumberProps);
    this.setNumber2.hideIndex();

    var setNumber3Data = [
      [, ],
      [, ],
      [, ],
    ];
    setNumberProps.data = setNumber3Data;
    this.setNumber3 = jspreadsheet(document.getElementById('setNumber3'), setNumberProps);
    this.setNumber3.hideIndex();

    this.setNumber4 = jspreadsheet(document.getElementById('setNumber4'), setNumberProps);
    this.setNumber4.hideIndex();

    this.setNumber5 = jspreadsheet(document.getElementById('setNumber5'), setNumberProps);
    this.setNumber5.hideIndex();

    this.setNumber6 = jspreadsheet(document.getElementById('setNumber6'), setNumberProps);
    this.setNumber6.hideIndex();

    this.setNumber7 = jspreadsheet(document.getElementById('setNumber7'), setNumberProps);
    this.setNumber7.hideIndex();

    this.setNumber8 = jspreadsheet(document.getElementById('setNumber8'), setNumberProps);
    this.setNumber8.hideIndex();

    this.setNumber9 = jspreadsheet(document.getElementById('setNumber9'), setNumberProps);
    this.setNumber9.hideIndex();

  }

}
</script>
