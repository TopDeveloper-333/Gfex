<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Economics for the FDP</u></p>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <div id="economics1Sheet"></div>
      <div id="economics2Sheet"></div>
      <div id="economics3Sheet"></div>
      <div id="economicsDataSheet"></div>
    </div>

  </div>  
</template>

<script>
import store from '~/store'
import { mapState } from 'vuex'

export default {
  name: 'Economics',
  
  middleware: 'auth',
  
  props: {
    title: {type: String, default: null}
  },

  data() {
    return {
      economics1Sheet: null,
      economics2Sheet: null,
      economics3Sheet: null,
      economicsDataSheet: null
    }
  },

  computed: {
    ...mapState({

    })
  },

  methods: {
    onSavePage: async function(event) {
      console.log("Economics's onSavePage() is called")
    }
  },

  mounted() {

    // Economics 1 data
    var economics1Data = [
      [3.00, 40, 0.50, 50, 0.60]
    ];

    this.economics1Sheet = jspreadsheet(document.getElementById('economics1Sheet'), {
        data:economics1Data,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Price of Gas ($/MMBTU)',
                width: 300,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Price of Condensate ($/barrels)',
                width: 380,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Price of Compression ($/MMscf/psi)',
                width: 430,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Life',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Salvage rate of the CAPEX',
                width: 320,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.economics1Sheet.hideIndex();
    
    // Economics 2 data
    var economics2Data = [
      [2500, 0.12, 0.20, 0.40]
    ];

    this.economics2Sheet = jspreadsheet(document.getElementById('economics2Sheet'), {
        data:economics2Data,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Investment (Budget $MM)',
                width: 330,
                decimal:','
            },
            {
                type: 'numeric',
                title:'ROR',
                width: 90,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Royalty (% of Revenues)',
                width: 320,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Tax (% of Taxable Revenues)',
                width: 350,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.economics2Sheet.hideIndex();

    // Economics 3 data
    var economics3Data = [
      [2020, 2020]
    ];

    this.economics3Sheet = jspreadsheet(document.getElementById('economics3Sheet'), {
        data:economics3Data,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'1st Year of Project',
                width: 240,
                decimal:','
            },
            {
                type: 'numeric',
                title:'1st Year of Production',
                width: 380,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.economics3Sheet.hideIndex();

    // Economics data
    var economicsData = [
      [1, 100, 0.0],
      [2, 100, 0.0],
      [3, 100, 0.0],
      [4, 100, 0.0],
      [5, 100, 0.0],
      [6, 100, 0.0],
      [7, 0, 30.0],
      [8, 0, 30.0],
      [9, 0, 30.0],
      [10, 0, 30.0],
      [11, 0, 30.0],
      [12, 0, 30.0],
      [13, 0, 30.0],
      [14, 0, 30.0],
      [15, 0, 30.0],
      [16, 0, 30.0],
      [17, 0, 30.0],
      [18, 0, 30.0],
      [19, 0, 30.0],
      [20, 0, 30.0],
      [21, 0, 30.0],
    ];

    this.economicsDataSheet = jspreadsheet(document.getElementById('economicsDataSheet'), {
        data:economicsData,
        allowInsertRow:true,
        allowManualInsertRow:true,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:true,
        allowDeleteColumn:false,
        tableOverflow: true,
        columns: [
            {
                type: 'numeric',
                title:'Year',
                width: 100,
                decimal:','
            },
            {
                type: 'numeric',
                title:'CAPEX (MM $)',
                width: 200,
                decimal:','
            },
            {
                type: 'numeric',
                title:'OPEX (MM $)',
                width: 180,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.economicsDataSheet.hideIndex();
  }
}
</script>
