<template>
  <div class="row">
    <div class="m-auto">
    <form>
      <div class="card mb-3">
        <div class="card-header gf-header">
          FastPlan* Platform<br>
          <p style="font-size:3rem !important">Conventional and Shale Reservoirs</p>
        </div>
        <div class="row g-0" style="background-color:#fdf500;">
          <div class="col-md-4" style="display:flex; justify-content:center;">
            <img src="/assets/image/LOGO_GFEX.png" class="img-fluid rounded-start" style="opacity:0.6;max-width:250px;max-height:300px">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h3 class="card-title gf-title"><{{projectName}}> Field Project</h3>
              <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>Gas Condensate PVT Data</u></p>

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <div id="gasCondensate1"></div>
              </div>

              <div style="display:flex;margin-bottom:6px;text-align:center" class="row">
                <div id="gasCondensate2"></div>
              </div>

              <div>
                <label class="btn btn-primary btn-simple active gf-button" v-on:click="onPrevPage">Previous</label>
                <label class="btn btn-primary btn-simple active gf-button" style="float:right" v-on:click="onNextPage">Next</label>
              </div>

            </div>
          </div>
        </div>
      </div>
    </form>

    </div>
  </div>
</template>

<script>
import store from '~/store'
import { mapState } from 'vuex'


// import axios from 'axios'
export default {
  middleware: 'auth',

  // async asyncData () {
  //   const { data: projects } = await axios.get('/api/projects')

  //   return {
  //     projects
  //   }
  // },

  metaInfo () {
    return { title: this.$t('GAS Condensate') }
  },

  data() {
    return {
      gasCondensate1: null,
      gasCondensate2: null,
      myGasCondensate : {}
    }
  },

  computed: {
    ...mapState({
      projectName : state => state.project.projectName,
      gasCondensate : state => state.project.gascondensate
    }),
  },

  methods: {
    onPrevPage: function(event) {
      this.$router.go(-1)
    },
    onNextPage: async function(event) {
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

    }
  },
  mounted() {
    this.myGasCondensate = this.gascondensate

    var gasCondensate1Data = [
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
                title:'BO (rb/stb)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'RS (scf/stb)',
                width: 120,
                decimal:','
            },
            {
                type: 'numeric',
                title:'BG (rb/mscf)',
                width: 140,
                decimal:','
            },
            {
                type: 'numeric',
                title:'RV (stb/mmscf)',
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
                title:'Gas Viscpsity (cp)',
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
