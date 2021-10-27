<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Reservoir</u></p>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Reservoir PVT</p>
      <div id="reservoirPVTSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Reservoir Parameters</p>
      <div id="reservoirDataSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Dual-Porosity</p>
      <multiselect v-model="dualPorosity" :options="dualPorosityOptions" track-by="name" label="name" placeholder="Select option"></multiselect>
      <div id="dualPorositySheet" style="margin-top:1rem"  v-show="dualPorosity != null && dualPorosity.value ==1"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Well-Test Data</p>
      <multiselect v-model="testWellData" :options="testWellDataOptions" track-by="name" label="name" placeholder="Select option"></multiselect>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row" v-show="testWellData != null && testWellData.value == 1">
      <p class="gf-item">C & N Method</p>
      <div id="cnMethodSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row" v-show="testWellData != null && testWellData.value == 2">
      <p class="gf-item">Vertical Model</p>
      <div id="verticalModelSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row" v-show="testWellData != null && testWellData.value == 3">
      <p class="gf-item">Horizontal Model</p>
      <div id="horizontalModelSheet"></div>
    </div>

  </div>  
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
.multiselect {
  max-width: 370px;
  /* margin: auto; */
}
</style>

<script>
import store from '~/store'
import { mapState } from 'vuex'
import Multiselect from 'vue-multiselect'

export default {
  name: 'Reservoir',
  
  middleware: 'auth',

  components: {
    Multiselect
  },
  
  props: {
    title: {type: String, default: null}
  },

  data() {
    return {
      reservoirPVTSheet: null,
      reservoirDataSheet : null,
      dualPorositySheet: null,
      testWellDataSheet: null,
      cnMethodSheet: null,
      verticalModelSheet: null,
      horizontalModelSheet: null,
      dualPorosity : { name: "No", value : 0},
      testWellData : { name: "C & N Method", value : 1 },
      dualPorosityOptions: [
        { name: "Yes", value : 1 },
        { name: "No", value : 0} 
      ],
      testWellDataOptions: [
        { name: "C & N Method", value : 1 },
        { name: "Vertical Model", value: 2 },
        { name: "Horizontal Model", value: 3 }
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
     
    // ----------------------------------------------------
    // Reservoir PVT sheet
    var reservoirPVTData = [
      [0.019, 0.95, 0.6, 250]
    ];

    this.reservoirPVTSheet = jspreadsheet(document.getElementById('reservoirPVTSheet'), {
        data:reservoirPVTData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Viscosity (psia)',
                width: 160,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Gas Z-Factor',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Specific Gravity',
                width: 160,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Reservoir Temperature (Deg F)',
                width: 280,
                decimal:','
            },
        ]
    });
    this.reservoirPVTSheet.hideIndex();

    // ----------------------------------------------------
    // Reservoir Data sheet
    var reservoirData = [
      [15000, 5114]
    ];

    this.reservoirDataSheet = jspreadsheet(document.getElementById('reservoirDataSheet'), {
        data:reservoirData,
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
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Reservoir Pressure (psia)',
                width: 240,
                decimal:','
            },
        ]
    });
    this.reservoirDataSheet.hideIndex();

    // ----------------------------------------------------
    // Dual Porosity Data sheet
    var dualPorosityData = [
      [100, 300, 1.0, 5000]
    ];

    this.dualPorositySheet = jspreadsheet(document.getElementById('dualPorositySheet'), {
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
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'hm (ft)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Shape Factor Sigma',
                width: 200,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Matrix GIIP (bcf)',
                width: 200,
                decimal:','
            },
        ]
    });
    this.dualPorositySheet.hideIndex();

    // ----------------------------------------------------
    // C & N Method
    var cnMethodData = [
      [26.9, 0.537]
    ];

    this.cnMethodSheet = jspreadsheet(document.getElementById('cnMethodSheet'), {
        data:cnMethodData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'C (Mscf/day/psia2)',
                width: 190,
                decimal:','
            },
            {
                type: 'numeric',
                title:'N',
                width: 120,
                decimal:','
            },
        ]
    });
    this.cnMethodSheet.hideIndex();

    // ----------------------------------------------------
    // Vertical Model
    var verticalModelData = [
      [1, 0.2, 100, 72, 6, 0]
    ];

    this.verticalModelSheet = jspreadsheet(document.getElementById('verticalModelSheet'), {
        data:verticalModelData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
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
    });
    this.verticalModelSheet.hideIndex();

    // ----------------------------------------------------
    // Horizontal Model
    var horizontalModelData = [
      [1, 0.2, 100, 72, 6, 0, 2000, 0.01]
    ];

    this.horizontalModelSheet = jspreadsheet(document.getElementById('horizontalModelSheet'), {
        data:horizontalModelData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
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
    });
    this.horizontalModelSheet.hideIndex();
  }
}
</script>
