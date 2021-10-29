<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Operations</u></p>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Operational Constraints</p>
      <div id="operationalConstraintsSheet"></div>
    </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">Gas Delivery Requirements</p>
      <div id="gasDeliveryReqSheet"></div>
    </div>

  </div>
</template>

<script>
import store from '~/store'
import { mapState } from 'vuex'


export default {
  name: 'Oprations',
  
  middleware: 'auth',
  
  props: {
    title: {type: String, default: null}
  },

  data() {
    return {
      operationalConstraintsSheet: null,
      gasDeliveryReqSheet: null,
      isOperationalValidate: true,
      isGasDeliveryValidate: true
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
      return this.isOperationalValidate & this.isGasDeliveryValidate
    }
  },

  methods: {

  },

  mounted() {

    // ----------------------------------------------------
    // Operational Constraints
    var operationalConstraintsData = [
      [2020, 0, 100, 4]
    ];

    this.operationalConstraintsSheet = jspreadsheet(document.getElementById('operationalConstraintsSheet'), {
        data:operationalConstraintsData,
        allowInsertRow:false,
        allowManualInsertRow:false,
        allowInsertColumn:false,
        allowManualInsertColumn:false,
        allowDeleteRow:false,
        allowDeleteColumn:false,
        columns: [
            {
                type: 'numeric',
                title:'Start of Operations',
                width: 240,
                decimal:','
            },
            {
                type: 'numeric',
                title:'End of Contract',
                width: 200,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Maximum Number of Wells',
                width: 330,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Rig Schedule (Infill Wells per Year)',
                width: 420,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.operationalConstraintsSheet.hideIndex();

    // ----------------------------------------------------
    // Gas Delivery Requirements
    var gasDeliveryReqData = [
      [100, 300, 800, 2.0, 0.85]
    ];

    this.gasDeliveryReqSheet = jspreadsheet(document.getElementById('gasDeliveryReqSheet'), {
        data:gasDeliveryReqData,
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
                width: 260,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Target Rate (MMscf/day)',
                width: 300,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Pressure Limit (psia)',
                width: 280,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Economics Rate (MMscf/day)',
                width: 360,
                decimal:','
            },
            {
                type: 'numeric',
                title:'Max Field Recovery',
                width: 260,
                decimal:','
            },
        ],
        updateTable: this.validationTable
    });
    this.gasDeliveryReqSheet.hideIndex();
  }
}
</script>
