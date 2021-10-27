<template>
  <div>
    <p class="card-text" style="font-size: 2.6rem !important;text-align: center !important;"><u>Surface Data</u></p>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Tubing Properties</p>
      <div id="tubingPropertiesSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Pipeline Wellhead to Tie-Point/Manifold</p>
      <div id="wellaheadToManifoldSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Pipeline Tie-Point/Manifold to Compression Station</p>
      <div id="manifoldToCompressionSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Pipeline Compression Station to Sales Point</p>
      <div id="compressionToSalesSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Compression Discharge Ratio & Pressure Differential to Start Compression</p>
      <div id="compressionToStartSheet"></div>
    </div>

  </div>
</template>

<script>
import store from '~/store'
import { mapState } from 'vuex'

export default {

  middleware: 'auth',

  data() {
    return {
      tubingPropertiesSheet: null,
      wellaheadToManifoldSheet: null,
      manifoldToCompressionSheet: null,
      compressionToSalesSheet: null,
      compressionToStartSheet: null,
      mySurface : {}
    }
  },

  computed: {
    ...mapState({
    }),
  },

  methods: {
    onSavePage: async function(event) {

    }
  },

  mounted() {

    // Tubing Properties
    var tubingPropertiesData = [
      [10000, 4.5, 8500, 3, 500, 140, 0.98]
    ];

    this.tubingPropertiesSheet = jspreadsheet(document.getElementById('tubingPropertiesSheet'), {
        data:tubingPropertiesData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Length (ft)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Size (in)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Perfs (ft)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'SSSV_ID (in)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'SSSV_Depth (ft)',
                width: 150,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Temperature (Deg F)',
                width: 190,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Gas Z-Factor',
                width: 120,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.tubingPropertiesSheet.hideIndex();

    // WELLAHEAD -> Manifold
    var wellaheadToManifoldData = [
      [10000, 10, 60, 0.93]
    ];

    this.wellaheadToManifoldSheet = jspreadsheet(document.getElementById('wellaheadToManifoldSheet'), {
        data:wellaheadToManifoldData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Length (miles)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Size (in)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Temperature (Deg F)',
                width: 190,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Gas Z-Factor',
                width: 120,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.wellaheadToManifoldSheet.hideIndex();

    // Manifold -> Compression
    var manifoldToCompressionData = [
      [18.94, 10, 60, 0.93]
    ];

    this.manifoldToCompressionSheet = jspreadsheet(document.getElementById('manifoldToCompressionSheet'), {
        data:manifoldToCompressionData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Length (miles)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Size (in)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Temperature (Deg F)',
                width: 190,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Gas Z-Factor',
                width: 120,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.manifoldToCompressionSheet.hideIndex();

    // Compression -> Sales
    var compressionToSalesData = [
      [0.1894, 10, 60, 0.93]
    ];

    this.compressionToSalesSheet = jspreadsheet(document.getElementById('compressionToSalesSheet'), {
        data:compressionToSalesData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Length (miles)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Size (in)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Temperature (Deg F)',
                width: 190,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Gas Z-Factor',
                width: 120,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.compressionToSalesSheet.hideIndex();

    // Compression -> Start
    var compressionToStartData = [
      [1, 100]
    ];

    this.compressionToStartSheet = jspreadsheet(document.getElementById('compressionToStartSheet'), {
        data:compressionToStartData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Compression Discharge Ratio',
                width: 280,
                decimal:','
            },
            {
                type: 'numeric',
                title:'DELTA_P_MIN (psia)',
                width: 190,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.compressionToStartSheet.hideIndex();

  }

}
</script>
