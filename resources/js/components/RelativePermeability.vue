<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Relative Permeability Data via Corey Function</u></p>

    <div>
      <label class="btn btn-primary gf-button" style="float:right;margin-left:10px" v-on:click="onPlot">Plot</label>
      <label class="btn btn-primary gf-button" style="float:right" v-on:click="onCalculate">Calculate</label>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
      <div id="relativePermeabilitySheet"></div>
      <div id="responseKGKOSheet"></div>
    </div>

  </div>
</template>
<script>
import store from '~/store'
import { mapState } from 'vuex'

export default {
  name: 'RelativePermeability',
  middleware: 'auth',

  props: {
    title: { type: String, default: null }
  },
  
  data() {
    return {
      relativePermeabilitySheet: null,
      responseKGKOSheet: null,
      isRelPermValidate: true
    }
  },

  watch: {
    isRelPermValidate: function(val, oldVal) {
      this.$emit('changedValidate', val)
    }
  },

  computed: {
    ...mapState({
      resKGKO : state => state.project.resKGKO,
    }),
  },

  methods: {
    onCalculate: async function(event) {

      let corey = {}
      corey.sgi = this.relativePermeabilitySheet.getValue('A1');
      corey.krgl = this.relativePermeabilitySheet.getValue('B1');
      corey.kroi = this.relativePermeabilitySheet.getValue('C1');
      corey.sor = this.relativePermeabilitySheet.getValue('D1');
      corey.lambda = this.relativePermeabilitySheet.getValue('E1');

      await store.dispatch('project/fetchKGKO', corey)

      debugger
      this.responseKGKOSheet.setData(this.resKGKO)
      this.responseKGKOSheet.refresh()

    },
    onPlot: function(event) {
      alert('plot is called')
    },
    validateRelPerm: function(instance, cell, col, row, val, label, cellName) {
      var value = parseFloat(val)

      if (cellName == 'A1') {
        // this means start to update table
        this.isRelPermValidate = true
      }

      if ((isNaN(value) == true) || (value < 0) || 
          (col < 4 && !(value >=0 && value <=1) )) 
      {
        this.markInvalidCell(cell)
        this.isRelPermValidate = false
      }
      else {
        this.markNormalCell(cell)
      }

    },
  },

  mounted() {
    var relativePermeabilityData = [
      // [,],
      [0, 0.0, 1.0, 0.40, 2.0]
    ];

    this.relativePermeabilitySheet = jspreadsheet(document.getElementById('relativePermeabilitySheet'), {
        data:relativePermeabilityData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Sgi',
                width: 90,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Krgl',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Kroi',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Sor',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Lambda',
                width: 140,
                decimal:','
            },
        ],
        updateTable: this.validateRelPerm
    });
    this.relativePermeabilitySheet.hideIndex();

    debugger
    this.responseKGKOSheet = jspreadsheet(document.getElementById('responseKGKOSheet'), {
        data:this.resKGKO,
        allowInsertRow:true,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:true,
        allowDeleteColumn:false,
        tableOverflow: true,
        columns: [
            {
                type: 'numeric',
                title:'Sg',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Krg',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Kro',
                width: 120,
                decimal:','
            },
        ],
    });
    this.responseKGKOSheet.hideIndex();
  }

}
</script>
<style scoped>
.jexcel > thead > tr > td {
  font-size: 20px;
}
</style>