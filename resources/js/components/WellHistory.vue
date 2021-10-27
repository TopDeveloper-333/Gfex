<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Historical Input Data</u></p>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Reservoir Data</p>
      <div id="reservoirDataHSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Dual-Porosity</p>
      <multiselect v-model="ifracture" :options="ifractureOptions" track-by="name" label="name" placeholder="Select option"></multiselect>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row" v-show="ifracture!=null && ifracture.value==1">
      <div id="ifracture1Sheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">History & Forecast Runs</p>
      <div id="ifracture2Sheet"></div>
    </div>
    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Operations Data</p>
      <div id="historyForecastSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Number of Wells in Network (Present & Futures)</p>
    </div>

    <div id="dynamicData">
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
      ifracture1Sheet: null,
      ifracture2Sheet: null,
      historyForecastSheet: null,
      ifracture: null,
      ifractureOptions: [
        {name: "Yes", value: 1},
        {name: "No", value: 0}
      ]
    }
  },

  computed: {
    ...mapState({

    })
  },

  methods: {

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
        ]
    });
    this.reservoirDataHSheet.hideIndex();

    // IFracture 1 data
    var ifracture1Data = [
      [1, 300, 0.00001, 15000]
    ];

    this.ifracture1Sheet = jspreadsheet(document.getElementById('ifracture1Sheet'), {
        data:ifracture1Data,
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
        ]
    });
    this.ifracture1Sheet.hideIndex();

    // IFracture 2 data
    var ifracture2Data = [
      [2020, 50]
    ];

    this.ifracture2Sheet = jspreadsheet(document.getElementById('ifracture2Sheet'), {
        data:ifracture2Data,
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
        ]
    });
    this.ifracture2Sheet.hideIndex();

    // History & Forecast Runs
    var historyForecastData = [
      [100, 800, 0.01, 70]
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
        ]
    });
    this.historyForecastSheet.hideIndex();

  }
}
</script>
