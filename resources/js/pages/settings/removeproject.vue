<template>
  <card :title="$t('Project Management')">
    <vue-confirm-dialog></vue-confirm-dialog>
    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
    
      <p class="gf-item">Remove Projects and Plots</p>
      
      <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
        <p class="gf-item">Project List</p>
        <vue-good-table :columns="projectColumns" :rows="projectList" 
                        max-height="300px"                      
                        :search-options="{enabled: true}"
                        :fixed-header="true"
                        :pagination-options="{enabled: true,mode: 'pages'}">
          <template slot="table-row" slot-scope="props">
            <span v-if="props.column.field == 'action'">
              <label class="btn btn-danger" v-on:click="onDeleteProject(props.row)">Delete</label>
            </span>
          </template>
        </vue-good-table>
      </div>

      <div style="display:flex;margin-bottom:6px;text-align:left;margin-top:20px" class="row">
        <p class="gf-item">Plot List</p>
        <vue-good-table :columns="plotColumns" :rows="allPlotNames" 
                        max-height="300px"                      
                        :search-options="{enabled: true}"
                        :fixed-header="true"
                        :pagination-options="{enabled: true,mode: 'pages'}">
          <template slot="table-row" slot-scope="props">
            <span v-if="props.column.field == 'action'">
              <label class="btn btn-danger" v-on:click="onDeletePlot(props.row)">Delete</label>
            </span>
          </template>
        </vue-good-table>
      </div>

    </div>

  </card>
</template>

<script>
import Form from 'vform'
import store from '~/store'
import { mapState } from 'vuex'

export default {
  middleware: 'auth',

  components: {
  },

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data() {
    return {
      allPlotNames: [],
      projectColumns: [
          {
              label:'Id',
              field:'id',
              width:'50px',
          },
          {
              label:'Project Name',
              field:'name',
              width:'80px',
          },
          {
              label:'Action',
              field:'action',
              width:'80px',
          },
      ],
      plotColumns: [
          {
              label:'Id',
              field:'id',
              width:'50px',
          },
          {
              label:'Created',
              field:'group',
              width:'80px',
          },
          {
              label:'Plot Name',
              field:'name',
              width:'80px',
          },
          {
              label:'Action',
              field:'action',
              width:'80px',
          },
      ],
    }
  },
  computed:{
    ...mapState({
      projectList : state => state.project.projectList
    }),
  },

  async mounted () {
    this.isLoading = true
    await store.dispatch('project/listProjects')
    this.allPlotNames = await store.dispatch('project/listPlots')
    this.isLoading = false
  },

  methods: {
    async onDeleteProject(row) {
      this.$confirm({
        message: 'Are you sure to remove this project: "' + row.name + '"?',
        button: {
          no: 'No',
          yes: 'Yes'
        },
        callback: async confirm => {
          if (confirm) {
            let result = await store.dispatch('project/deleteProject', row)
            this.$router.go()
          }
        }
      })
    },
    async onDeletePlot(row) {
      this.$confirm({
        message: 'Are you sure to remove this plot: "' + row.name + '"?',
        button: {
          no: 'No',
          yes: 'Yes'
        },
        callback: async confirm => {
          if (confirm) {
            let result = await store.dispatch('project/deletePlot', row)
            this.$router.go()
          }
        }
      })
    }
  }
}

</script>
