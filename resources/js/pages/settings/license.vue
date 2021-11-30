<template>
  <card :title="$t('Users Management')">
    <vue-confirm-dialog></vue-confirm-dialog>
    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
    
      <p class="gf-item">License for Standalone</p>
      
      <!-- Name -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('name') }}</label>
        <div class="col-md-7">
          <input v-model="name" class="form-control" type="text" name="name">
        </div>
      </div>

      <!-- Email -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('email') }}</label>
        <div class="col-md-7">
          <input v-model="email" class="form-control" type="email" name="email" id="email">
        </div>
      </div>

      <!-- User Role -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('User Role') }}</label>
        <div class="col-md-7">
          <select class="form-select" name="role" v-model="role">
            <option selected value="0">Permanent</option>
            <option value="1">Daily-based</option>
          </select>
        </div>
      </div>

      <!-- From To -->
      <div class="mb-3 row" v-show="role != undefined && role == 1">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('From') }}</label>
        <div class="col-md-3">
          <input v-model="from" class="form-control" type="date" name="from" id="from">
        </div>
        <label class="col-md-1 col-form-label text-md-end">{{ $t('To') }}</label>
        <div class="col-md-3">
          <input v-model="to" class="form-control" type="date" name="to" id="to">
        </div>
      </div>

      <!-- Email -->
      <div class="mb-3 row">
        <label class="col-md-3 col-form-label text-md-end">{{ $t('MachineKey') }}</label>
        <div class="col-md-7">
          <input class="form-control" type="file" id="machineKey" @change="onFilePicked">
        </div>
      </div>

      <!-- Submit Button -->
      <div class="mb-3 row">
        <div class="col-md-9 ms-md-auto">
          <label class="btn btn-primary" v-on:click="generateLicense()">Create License</label>
        </div>
      </div>

    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
      <p class="gf-item">License List</p>
      <vue-good-table :columns="columns" :rows="licenses" 
                      max-height="300px"                      
                      :search-options="{enabled: true}"
                      :fixed-header="true"
                      :pagination-options="{enabled: true,mode: 'pages'}">
        <template slot="table-row" slot-scope="props">
          <span v-if="props.column.field == 'action'">
            <label class="btn btn-primary" v-on:click="onDownloadLicense(props.row)">Download</label>
            <label class="btn btn-danger" v-on:click="onDeleteLicense(props.row)">Delete</label>
          </span>
          <span v-if="props.column.field == 'role'">
            <label v-show="`${props.row.role}` == 0">Permenant</label>
            <label v-show="`${props.row.role}` == 1">Daily-based</label>
          </span>
          <span v-else>
            {{props.formattedRow[props.column.field]}}
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
      name:'',
      email: '',
      from: '',
      to: '',
      machineKey: '',
      role: 1,
      columns: [
          {
              label:'Id',
              field:'id',
              width:'50px',
          },
          {
              label:'Name',
              field:'name',
              width:'80px',
          },
          {
              label:'Email',
              field:'email',
              width:'80px',
          },
          {
              label:'Role',
              field:'role',
              width:'80px',
              filterOptions: {
                enabled: true,
                placeholder: "Filter User's Role",
                filterDropdownItems: [  
                  { value: 0, text: 'Permenant' },  
                  { value: 1, text: 'Daily-based' },  
                ],
                trigger: 'enter', //only trigger on enter not on keyup 
              },
          },
          {
              label:'From',
              field:'from',
              width:'90px',
          },
          {
              label:'To',
              field:'to',
              width:'90px',
          },
          {
              label:'OS',
              field:'ostype',
              width:'60px',
          },
          {
              label:'MachineKey',
              field:'machine_key',
              width:'160px',
          },
          {
              label:'LicenseKey',
              field:'license_key',
              width:'320px',
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
      licenses : state => state.project.licenses,
    }),
  },

  async mounted () {
    await store.dispatch('project/fetchLicenses', 0)
  },

  methods: {
    generateLicenseKey(licenseKey) {
      var element = document.createElement('a');
      element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(licenseKey));
      element.setAttribute('download', "licenseKey.pem");

      element.style.display = 'none';
      document.body.appendChild(element);

      element.click();

      document.body.removeChild(element);
    },
    async onDownloadLicense(row) {
      this.generateLicenseKey(row.license_key)
    },
    async onDeleteLicense(row) {
      this.$confirm({
        message: 'Are you sure to remove this license: "' + row.name + '"?',
        button: {
          no: 'No',
          yes: 'Yes'
        },
        callback: async confirm => {
          if (confirm) {
            let result = await store.dispatch('project/deleteLicense', row)
            this.$router.go()
          }
        }
      })
    },
    onFilePicked(event) {
      const file = event.target.files[0]

      const reader = new FileReader();
      reader.onload = e => {
        this.machineKey = e.target.result
        console.log(e.target.result);
      }
      reader.readAsText(file);
    },
    async generateLicense() {

      if (this.machineKey == '') {
        alert('Please upload machinekey file')
        document.getElementById('machineKey').focus();
        return
      }

      if (this.role == '1' && (this.to == '' || this.from == '')) {
        alert('Please input start and end date')
        if (this.from == '') 
          document.getElementById('from').focus();
        else if (this.to == '')
          document.getElementById('to').focus();
        return;
      }

      if (this.email == '') {
        alert('Please input email address')
        document.getElementById('email').focus();
        return
      }

      let result = await store.dispatch('project/generateLicense', {
        name: this.name,
        email: this.email,
        from: this.from,
        to: this.to,
        role: this.role,
        machineKey: this.machineKey
      })

      if (result != undefined && result.license_key != undefined)
      {
        this.generateLicenseKey(result.license_key)
        this.$router.go()
      }

    }
  }
}

</script>
