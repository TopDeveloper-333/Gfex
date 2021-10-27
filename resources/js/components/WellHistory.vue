<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Historical Input Data</u></p>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Reservoir Data</p>
      <div id="reservoirDataHSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Dual-Porosity</p>
      <multiselect v-model="dualPorosity" :options="dualPorosityOptions" track-by="name" label="name" placeholder="Select option"></multiselect>
      <div id="dualPorosityHSheet" style="margin-top:1rem" v-show="dualPorosity != null && dualPorosity.value ==1"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">History & Forecast Runs</p>
      <div id="historyForecastSheet"></div>
    </div>
    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Operations Data</p>
      <div id="operationsDataSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Number of Wells in Network (Present & Futures)</p>
      <input class="form-control gf-control" style="width:500px; margin-left:10px; margin-bottom:20px" type="number"
          maxlength="20" v-model="numberOfWells" placeholder="Please input numbers of Wells">
    </div>

    <div v-for="entry in wellsNetwork" :key="entry.id"
      style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <!-- {{entry.id}} : {{ entry.option }} : {{entry.data}} -->
      <hr class="gf-line">

      <p class="gf-item">Well-Test Data #{{entry.id + 1}}</p>
      <multiselect v-model="entry.option" :options="testWellDataOptions" @select="onChangedOption"
        track-by="name" label="name" placeholder="Select option" :id="'option-' + entry.id"></multiselect>

      <!-- <p class="gf-item" v-show="entry.option!=null&&entry.option.value==1">C & n Model #{{entry.id + 1}}</p>
      <p class="gf-item" v-show="entry.option!=null&&entry.option.value==2">Vertical Well Model #{{entry.id + 1}}</p>
      <p class="gf-item" v-show="entry.option!=null&&entry.option.value==3">Horizontal Well Model #{{entry.id + 1}}</p> -->

      <div :id="'networkSheet-' + entry.id" style="margin-top:20px"></div>
    </div>

  </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
.multiselect {
  max-width: 480px;
  /* margin: auto; */
}
</style>

<script>
import store from '~/store'
import { mapState } from 'vuex'
import Multiselect from 'vue-multiselect'


export default {
  name: 'WellHistory',
  
  middleware: 'auth',
  
  props: {
    title: {type: String, default: null}
  },

  components: {
    Multiselect
  },

  data() {
    return {
      reservoirDataHSheet: null,
      dualPorosityHSheet: null,
      historyForecastSheet: null,
      operationsDataSheet: null,
      dualPorosity: {name: "No", value: 0},
      dualPorosityOptions: [
        {name: "Yes", value: 1},
        {name: "No", value: 0}
      ],
      numberOfWells: 0,
      wellsNetwork: [],
      testWellDataOptions: [
        { name: "C & n Model", value : 1 },
        { name: "Vertical Model", value: 2 },
        { name: "Horizontal Model", value: 3 }
      ],
    }
  },

  computed: {
    ...mapState({

    })
  },

  watch: {
    numberOfWells: function(val, oldVal) {
      if (val < 0)
        val = 0;
      if (oldVal < 0)
        oldVal = 0;

      this.numberOfWells = val;

      debugger
      if (val > oldVal) {
        // add new entry to wellsNetwork
        for (let index = 0; index < (val - oldVal); index++) {
          this.wellsNetwork.push(
            {
              id: this.wellsNetwork.length, 
              option:null, 
              sheet: null
            }
          )
        }
      }
      else if (val < oldVal) {
        // remove entry from wellsNetwork
        for (let index = 0; index < (oldVal - val); index++) {
          this.wellsNetwork.pop()
        }
      }
    },
  },

  methods: {
    onChangedOption: function(selectedOption, id) {

      var index = id.replace('option-','');
      let divId = 'networkSheet-' + index;
      var columns = []

      if (index >= this.wellsNetwork.length) {
        alert('Selected Id is out of number.')
        return;
      }

      if (selectedOption.value == 1) {
        columns = [
            {
                type: 'numeric',
                title:'C (Mscf/day/psia2)',
                width: 190,
                decimal:','
            },
            {
                type: 'numeric',
                title:'n',
                width: 120,
                decimal:','
            },
        ]
      }
      else if (selectedOption.value == 2) {
        columns = [
            {
                type: 'numeric',
                title:'k (md)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Porosity',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Net Pay (ft)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Drainage Area (acres)',
                width: 220,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Wellbore ID (in)',
                width: 160,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Skin',
                width: 90,
                decimal:','
            },
        ]
      }
      else if (selectedOption.value == 3) {
        // Horizontoal Well Model
        columns = [
            {
                type: 'numeric',
                title:'k (md)',
                width: 90,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Porosity',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Net Pay (ft)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Drainage Area (acres)',
                width: 220,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Wellbore ID (in)',
                width: 160,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Skin',
                width: 90,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Well Length',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Kv/Kh',
                width: 90,
                decimal:','
            },
        ]
      }

      document.getElementById(divId).innerHTML = '';
      this.wellsNetwork[index].sheet = jspreadsheet(document.getElementById(divId), {
          data:[[]],
          allowInsertRow:false,
          allowManualInsertRow:false,
          allowInsertColumn:false,
          allowManualInsertColumn:false,
          allowDeleteRow:false,
          allowDeleteColumn:false,
          columns: columns,
          updateTable: this.validationTable
      });
      this.wellsNetwork[index].sheet.hideIndex();
    }
  },

  mounted() {
    
    // Reservoir Data (Well History)
    var reservoirHData = [
      [10000, 5114]
    ];

    this.reservoirDataHSheet = jspreadsheet(document.getElementById('reservoirDataHSheet'), {
        data:reservoirHData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'GIIP (Bcf)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Pr (psia)',
                width: 140,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.reservoirDataHSheet.hideIndex();

    // dualPorosity data
    var dualPorosityData = [
      [1, 300, 0.00001, 15000]
    ];

    this.dualPorosityHSheet = jspreadsheet(document.getElementById('dualPorosityHSheet'), {
        data:dualPorosityData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'km (md)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'hm (ft)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Sigma',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Matrix GIIP (bcf)',
                width: 160,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.dualPorosityHSheet.hideIndex();

    // History & Broadcast data
    var historyForecastData = [
      [2020, 50]
    ];

    this.historyForecastSheet = jspreadsheet(document.getElementById('historyForecastSheet'), {
        data:historyForecastData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'First Year of Production',
                width: 220,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Life of the Field',
                width: 140,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.historyForecastSheet.hideIndex();

    // Operations Data
    var operationsData = [
      [100, 800, 0.01, 70]
    ];

    this.operationsDataSheet = jspreadsheet(document.getElementById('operationsDataSheet'), {
        data:operationsData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Sales Pressure (psia)',
                width: 200,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Pressure Limit (psia)',
                width: 200,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Economic Rate (MMscf/day)',
                width: 260,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Qgtot initial (MMscf/day)',
                width: 240,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.operationsDataSheet.hideIndex();

  }
}
</script>
