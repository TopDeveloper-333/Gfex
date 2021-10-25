<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Gas Condensate PVT Data</u></p>

    <div>
      <label class="btn btn-primary gf-button" style="float:right" v-on:click="onPlotPage">Plot</label>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
      <div id="gasCondensate1"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
      <div id="gasCondensate2"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:center;min-height:400px" class="row" v-show="bShowPlot == true">
      <div class="col-3">
        <label class="typo__label gf-item">Axis X:</label>
        <multiselect v-model="axisX" :options="options" placeholder="Select X axis."></multiselect>
        <label class="typo__label gf-item">Axis Y:</label>
        <multiselect v-model="axisY" :close-on-select="false" :options="options" :maxHeight="250" :multiple="true" :taggable="true" placeholder="Select multiple Y axis."></multiselect>
      </div>
      <div class="col-2">
        <label class="btn btn-primary gf-button" style="margin-top:85px" v-on:click="onShow">Show</label>
      </div>
      <div id="plot" class="col-7">
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
      gasCondensate1: null,
      gasCondensate2: null,
      myGasCondensate : {},
      bShowPlot : false,
      options: ["P (psia)", "Bo (rb/stb)", "Rs (scf/stb)", "Bg (rb/mscf)", "Rv (stb/mmscf)", "Oil Viscosity (cp)", "Gas Viscosity (cp)", "PV Inj (%)"],
      axisX: null,
      axisY: [],
      plot: null
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      gasCondensate : state => state.project.gascondensate,
      isEconomics : state => state.project.isEconomics,
      isFDP: state => state.project.isFDP,
    }),
  },

  methods: {
    onShow: function(event) {
      this.updatePlot();
    },
    onPlotPage: function(event) {
      if(this.bShowPlot == false)
        this.bShowPlot = true
      else
        this.bShowPlot = false
    },
    onSavePage: async function(event) {
      this.myGasCondensate = {
        gasCondensate1 : {
          Psat: 0, Swi : 0
        },
        gasCondensate2 : [
        ]
      };

      this.myGasCondensate.gasCondensate1.Psat = this.gasCondensate1.getValue('A1');
      this.myGasCondensate.gasCondensate1.Swi = this.gasCondensate1.getValue('B1');

      var numRows = this.gasCondensate2.options.data.length;
      for (var i =0; i < numRows; i++) {
        this.myGasCondensate.gasCondensate2[i] = [0, 0, 0, 0, 0, 0, 0, 0];
        this.myGasCondensate.gasCondensate2[i][0] = this.gasCondensate2.getValue('A' + (i+1));
        this.myGasCondensate.gasCondensate2[i][1] = this.gasCondensate2.getValue('B' + (i+1));
        this.myGasCondensate.gasCondensate2[i][2] = this.gasCondensate2.getValue('C' + (i+1));
        this.myGasCondensate.gasCondensate2[i][3] = this.gasCondensate2.getValue('D' + (i+1));
        this.myGasCondensate.gasCondensate2[i][4] = this.gasCondensate2.getValue('E' + (i+1));
        this.myGasCondensate.gasCondensate2[i][5] = this.gasCondensate2.getValue('F' + (i+1));
        this.myGasCondensate.gasCondensate2[i][6] = this.gasCondensate2.getValue('G' + (i+1));
        this.myGasCondensate.gasCondensate2[i][7] = this.gasCondensate2.getValue('H' + (i+1));
      }

      await store.dispatch('project/saveGasCondensate', this.myGasCondensate)
    },
    updatePlot: function() {
      this.plot = c3.generate({
          bindto: '#plot',
          data: {
            columns: [
              ['data1', 30, 200, 100, 400, 150, 250],
              ['data2', 50, 20, 10, 40, 15, 25]
            ],
            axes: {
              data2: 'y2' // ADD
            },
            types: {
              'data1': 'line',
              'data2': 'line'
            }
          },
          axis: {
            y2: {
              show: true // ADD
            }
          }
      });
    }
  },

  mounted() {
    this.myGasCondensate = this.gascondensate

    var gasCondensate1Data = [
      // [,],
      [5114.0, 0.0]
    ];

    this.gasCondensate1 = jspreadsheet(document.getElementById('gasCondensate1'), {
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
        ]
    });
    this.gasCondensate1.hideIndex();

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

    this.gasCondensate2 = jspreadsheet(document.getElementById('gasCondensate2'), {
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
        ]
    });
    this.gasCondensate2.hideIndex();
  }

}
</script>
