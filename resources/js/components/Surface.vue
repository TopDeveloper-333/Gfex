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
      mySurface : {},
      isTubingPropertiesValidate: true,
      isWellHeadToTieValidate: true,
      isTieToCompressionValidate: true,
      isCompressionToSalesValidate: true,
      isCompressionAndPressureValidate: true
    }
  },

  watch: {
    isDataValidate: function(val, oldVal) {
      this.$emit('changedValidate', val)
    }
  },

  computed: {
    ...mapState({
    }),
    isDataValidate: function() {
      return this.isTubingPropertiesValidate & this.isWellHeadToTieValidate &
          this.isTieToCompressionValidate & this.isCompressionToSalesValidate &
          this.isCompressionAndPressureValidate
    }
  },

  methods: {
    onSavePage: async function(event) {

    },
    validateTubingProperties:function(instance, cell, col, row, val, label, cellName) {
      var value = parseFloat(val)

      if (cellName == 'A1') {
        // this means start to update table
        this.isTubingPropertiesValidate = true
      }
      
      if ((isNaN(value) == true) || (value < 0) || 
          (col == 6 && !(value >=0 && value <=1)) ) 
      {
        this.markInvalidCell(cell)
        this.isTubingPropertiesValidate = false
      }
      else {
        this.markNormalCell(cell)
      }
    },
    validateWellHeadToTie:function(instance, cell, col, row, val, label, cellName) {
      var value = parseFloat(val)

      if (cellName == 'A1') {
        // this means start to update table
        this.isWellHeadToTieValidate = true
      }
      
      if ((isNaN(value) == true) || (value < 0) ||
          (col == 3 && !(value >=0 && value <=1)) ) 
      {
        this.markInvalidCell(cell)
        this.isWellHeadToTieValidate = false
      }
      else {
        this.markNormalCell(cell)
      }
    },
    validateTieToCompression:function(instance, cell, col, row, val, label, cellName) {
      var value = parseFloat(val)

      if (cellName == 'A1') {
        // this means start to update table
        this.isTieToCompressionValidate = true
      }
      
      if ((isNaN(value) == true) || (value < 0) ||
          (col == 3 && !(value >=0 && value <=1)) ) 
      {
        this.markInvalidCell(cell)
        this.isTieToCompressionValidate = false
      }
      else {
        this.markNormalCell(cell)
      }
    },
    validateCompressionToSales:function(instance, cell, col, row, val, label, cellName) {
      var value = parseFloat(val)

      if (cellName == 'A1') {
        // this means start to update table
        this.isCompressionToSalesValidate = true
      }
      
      if ((isNaN(value) == true) || (value < 0) ||
          (col == 3 && !(value >=0 && value <=1)) ) 
      {
        this.markInvalidCell(cell)
        this.isCompressionToSalesValidate = false
      }
      else {
        this.markNormalCell(cell)
      }
    },
    validateCompressionAndPressure:function(instance, cell, col, row, val, label, cellName) {
      var value = parseFloat(val)

      if (cellName == 'A1') {
        // this means start to update table
        this.isCompressionAndPressureValidate = true
      }
      
      if ((isNaN(value) == true) || (value < 0)) {
        this.markInvalidCell(cell)
        this.isCompressionAndPressureValidate = false
      }
      else {
        this.markNormalCell(cell)
      }
    },
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
        updateTable: this.validateTubingProperties
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
        updateTable: this.validateWellHeadToTie
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
        updateTable: this.validateTieToCompression
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
        updateTable: this.validateCompressionToSales
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
        updateTable: this.validateCompressionToSales
    });
    this.compressionToStartSheet.hideIndex();

  }

}
</script>
