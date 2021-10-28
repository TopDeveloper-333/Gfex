<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Gas Condensate PVT Data</u></p>

    <div>
      <label class="btn btn-primary gf-button" style="float:right" v-on:click="onPlotPage">{{plotLabel}}</label>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row" v-show="bShowPlot == false">
      <div id="gasCondensate1Sheet"></div>
      <div id="gasCondensate2Sheet"></div>
    </div>

    <div style="height:50px" v-show="bShowPlot == true">
    </div>

    <hr class="gf-line" v-show="bShowPlot == true">
    <div style="display:flex;margin-bottom:6px;text-align:center;min-height:600px" class="row" v-show="bShowPlot == true">
      <div class="col-3">
        <label class="typo__label gf-item">Axis X:</label>
        <multiselect v-model="axisX" :options="options" track-by="name" label="name" :taggable="true" placeholder="Select X axis."></multiselect>
        <label class="typo__label gf-item">Axis Y:</label>
        <multiselect v-model="axisY" :options="options" track-by="name" label="name" :maxHeight="250" :taggable="true" placeholder="Select Y axis."></multiselect>
        <!-- <multiselect v-model="axisY" :options="options" track-by="name" label="name" :close-on-select="false" :maxHeight="250" :multiple="true" :taggable="true" placeholder="Select multiple Y axis."></multiselect> -->
        <!-- <label class="typo__label gf-item">Axis Y2:</label>
        <multiselect v-model="axisY2" :options="options" track-by="name" label="name" :taggable="true" placeholder="Select Y2 axis."></multiselect> -->
      </div>
      <div class="col-2">
        <label class="btn btn-primary gf-button" style="margin-top:85px" v-on:click="onShow">Graph</label>
      </div>
      <div id="plot" class="col-7">
      </div>
    </div>

    <div id="plotModal" class="gf-modal">
      <div class="gf-modal-content">
        <div class="gf-modal-header">
          <span class="gf-comment" style="margin-left:30px;color:white">FastPlan* Gas platform</span>
          <span class="gf-close" id="plot-gf-close">&times;</span>
        </div>
        <p class="gf-comment" style="margin-top:6px !important; margin-bottom:6px !important;"><{{projectName}}> Field Project</p>
        <span style="font-size: 1.25rem">Please select all X, Y axes</span>
        <div style="margin-bottom:16px;margin-top:16px">
          <label class="btn btn-primary gf-button" v-on:click="onOK">OK</label>
        </div>
      </div>
    </div>

  </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
import store from '~/store'
import { mapState } from 'vuex'
import Multiselect from 'vue-multiselect'

export default {
  name: 'CondensatePvt',
  middleware: 'auth',

  components: {
    Multiselect
  },

  props: {
    title: { type: String, default: null }
  },
  
  data() {
    return {
      gasCondensate1Sheet: null,
      gasCondensate2Sheet: null,
      myGasCondensate : {},
      bShowPlot : false,
      options: [
        { name: "P (psia)", column: 'A'}, 
        { name: "Bo (rb/stb)", column: 'B'},
        { name: "Rs (scf/stb)", column: 'C'}, 
        { name: "Bg (rb/mscf)", column: 'D'},
        { name: "Rv (stb/mmscf)", column: 'E'}, 
        { name: "Oil Viscosity (cp)", column: 'F'}, 
        { name: "Gas Viscosity (cp)", column: 'G'},
        { name: "PV Inj (%)", column:'H'}
      ],
      axisX: null,
      axisY: [],
      axisY2: null,
      plot: null,
      plotLabel: "Plot",
      isGasCondensate1Validate: true,
      isGasCondensate2Validate: true,
    }
  },

  watch: {
    isDataValidate: function(val, oldVal) {
      this.$emit('changedValidate', val)
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      gasCondensate : state => state.project.gascondensate,
      isEconomics : state => state.project.isEconomics,
      isFDP: state => state.project.isFDP,
    }),
    isDataValidate: function() {
      return this.isGasCondensate1Validate & this.isGasCondensate2Validate
    }
  },

  methods: {
    onOK: function(event) {
        var modal = document.getElementById("plotModal");
        modal.style.display = "none";
    },
    onPlotPage: function(event) {
      if(this.bShowPlot == false) {
        this.bShowPlot = true
        this.plotLabel = "Data"
      }
      else {
        this.bShowPlot = false
        this.plotLabel = "Plot"
      }
    },
    onSavePage: async function(event) {
      this.myGasCondensate = {
        gasCondensate1 : {
          Psat: 0, Swi : 0
        },
        gasCondensate2 : [
        ]
      };

      this.myGasCondensate.gasCondensate1.Psat = this.gasCondensate1Sheet.getValue('A1');
      this.myGasCondensate.gasCondensate1.Swi = this.gasCondensate1Sheet.getValue('B1');

      var numRows = this.gasCondensate2Sheet.options.data.length;
      for (var i =0; i < numRows; i++) {
        this.myGasCondensate.gasCondensate2[i] = [0, 0, 0, 0, 0, 0, 0, 0];
        this.myGasCondensate.gasCondensate2[i][0] = this.gasCondensate2Sheet.getValue('A' + (i+1));
        this.myGasCondensate.gasCondensate2[i][1] = this.gasCondensate2Sheet.getValue('B' + (i+1));
        this.myGasCondensate.gasCondensate2[i][2] = this.gasCondensate2Sheet.getValue('C' + (i+1));
        this.myGasCondensate.gasCondensate2[i][3] = this.gasCondensate2Sheet.getValue('D' + (i+1));
        this.myGasCondensate.gasCondensate2[i][4] = this.gasCondensate2Sheet.getValue('E' + (i+1));
        this.myGasCondensate.gasCondensate2[i][5] = this.gasCondensate2Sheet.getValue('F' + (i+1));
        this.myGasCondensate.gasCondensate2[i][6] = this.gasCondensate2Sheet.getValue('G' + (i+1));
        this.myGasCondensate.gasCondensate2[i][7] = this.gasCondensate2Sheet.getValue('H' + (i+1));
      }

      await store.dispatch('project/saveGasCondensate', this.myGasCondensate)
    },
    onShow: function(event) {
      
      // ----------------------------------------------------------
      // Validation
      // ----------------------------------------------------------

      if (this.axisX == null || this.axisY.length == 0) {
        var modal = document.getElementById("plotModal");
        modal.style.display = "block";
        return;
      }

      // ----------------------------------------------------------
      // Initialize variables
      // ----------------------------------------------------------

      var axisX = this.axisX.name
      var columns = [
              // ['P (psia)', 5114, 4000, 3000, 2000, 1000],
              // ['Bo (rb/stb)', 5.094, 5.094, 1.66, 1.409, 1.219],
              // ['Oil Viscosity (cp)', 0.062, 0.062, 0.4781, 0.7746, 1.0295],
              // ['RS (scf/stb)', 7479.4, 7479.4, 1778.9, 1075.8, 596.4],
              // ['Bg (rb/mscf)', 0.681, 0.681, 0.958, 1.376, 2.636],
            ]
      var axes = {}
      var ylabel = null
      var ylabel2 = null

      // ----------------------------------------------------------
      // Start HERE
      // ----------------------------------------------------------
      var numRows = this.gasCondensate2Sheet.options.data.length;
      
      // ----------------------------------------------------------------
      // add x data
      columns[0] = []
      columns[0][0] = this.axisX.name
      for (let index = 1; index <= numRows; index++) {
        columns[0][index] = this.gasCondensate2Sheet.getValue(this.axisX.column + '' + index)        
      }
      
      // ----------------------------------------------------------------
      // add y data
      columns[1] = []
      columns[1][0] = this.axisY.name
      for (let index = 1; index <= numRows; index++) {
        columns[1][index] = this.gasCondensate2Sheet.getValue(this.axisY.column + '' + index)        
      }
      ylabel = this.axisY.name

      // ----------------------------------------------------------------
      // add y data : removed multiple axis Y
      // ----------------------------------------------------------------
      // var maxY = 0
      // for (let i = 0; i < this.axisY.length; i++) {

      //   columns[i+1] = []
      //   columns[i+1][0] = this.axisY[i].name
      //   for (let j = 1; j <= numRows; j++) {
      //     columns[i+1][j] = this.gasCondensate2Sheet.getValue(this.axisY[i].column + '' + j)

      //     if (maxY < columns[i+1][j]) {
      //       ylabel = this.axisY[i].name
      //       maxY = columns[i+1][j]
      //     }
      //   }
      // }

      // ----------------------------------------------------------------
      // add y2 data : removed axis Y2
      // ----------------------------------------------------------------
      // if (this.axisY2 != null) {
      //   columns[this.axisY.length+1] = []
      //   columns[this.axisY.length+1][0] = this.axisY2.name
      //   for (let j = 1; j <= numRows; j++) {
      //     columns[this.axisY.length+1][j] = this.gasCondensate2Sheet.getValue(this.axisY2.column + '' + j)
      //   }

      //   axes[this.axisY2.name] = 'y2'
      //   ylabel2 = this.axisY2.name
      // }

      this.updatePlot(axisX, columns, axes, ylabel, ylabel2);
    },
    updatePlot: function(_axisX, _columns, _axes, _ylabel, _ylabel2) {
      this.plot = c3.generate({
          bindto: '#plot',
          size: {
              height: 600,
          },
          data: {
            x: _axisX,
            columns: _columns,
            axes: _axes,
          },
          color: {
            pattern: ['#ffbb78']
          },
          legend: {
            position: 'inset',
            inset: {
              anchor: 'bottom-right',
              x: 20,
              y: 80,
            }
          },
          axis: {
            x: {
              label: {
                text: _axisX,
                position: 'outer-center'
              }
            },
            y: {
              label: {
                text: _ylabel,
                position: 'outer-middle'
              }
            },
            y2: {
              show: _ylabel2 != null, // ADD
              label: {
                text: _ylabel2,
                position: 'outer-middle'
              }
            }
          }
      });
    },
    validateGasCondensate1: function(instance, cell, col, row, val, label, cellName) {
      var value = parseFloat(val)

      if (cellName == 'A1') {
        // this means start to update table
        this.isGasCondensate1Validate = true
      }

      if (value < 0) {
        this.markInvalidCell(cell)
        this.isGasCondensate1Validate = false
      }
      else {
        this.markNormalCell(cell)
      }

      if (cellName == 'B1' && (value == 0 || value > 1)) {
        this.markInvalidCell(cell)
        this.isGasCondensate1Validate = false
      }

    },
    validateGasCondensate2: function(instance, cell, col, row, val, label, cellName) {

    },
  },

  mounted() {
    this.myGasCondensate = this.gascondensate

    var gasCondensate1Data = [
      // [,],
      [5114.0, 0.0]
    ];

    this.gasCondensate1Sheet = jspreadsheet(document.getElementById('gasCondensate1Sheet'), {
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        data:gasCondensate1Data,
        columns: [
            {
                type: 'numeric',
                title:'PSAT (psia)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Swi',
                width: 120,
                decimal:','
            },
        ],
        updateTable: this.validateGasCondensate1,
    });
    this.gasCondensate1Sheet.hideIndex();

    var gasCondensate2Data = [
      // [,,,,,,,],
      // [,,,,,,,],
      // [,,,,,,,],
      // [,,,,,,,],
      // [,,,,,,,]
      [5114.0, 5.094, 7479.4, 0.681, 133.7, 0.0620, 0.0620, 0.0],
      [4000.0, 5.094, 7479.4, 0.681, 133.7, 0.0620, 0.0620, 0.0],
      [3000.0, 1.660, 1778.9, 0.958, 57.1,  0.4781, 0.0222, 0.0],
      [2000.0, 1.409, 1075.8, 1.376, 31.2,  0.7746, 0.0166, 0.0],
      [1000.0, 1.219, 596.4,  2.636, 25.8,  1.0295, 0.0148, 0.0]
    ];

    this.gasCondensate2Sheet = jspreadsheet(document.getElementById('gasCondensate2Sheet'), {
        allowInsertRow:true,
        allowManualInsertRow:true,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:true,
        allowDeleteColumn:false,
        data:gasCondensate2Data,
        columns: [
            {
                type: 'numeric',
                title:'P (psia)',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Bo (rb/stb)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Rs (scf/stb)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Bg (rb/mscf)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Rv (stb/mmscf)',
                width: 150,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Oil Viscosity (cp)',
                width: 170,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Gas Viscosity (cp)',
                width: 170,
                decimal:','
            },
            {
                type: 'numeric',
                title:'PV Inj (%)',
                width: 120,
                decimal:','
            },
        ],
        updateTable: this.validateGasCondensate2,
    });
    this.gasCondensate2Sheet.hideIndex();

    mountPlotDialog();
  }

}

function mountPlotDialog() {

  // Get the modal
  var modal = document.getElementById("plotModal");

  // Get the <span> element that closes the modal
  var span = document.getElementById("plot-gf-close");

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
#plot {
  background: #ff320e;
}
</style>