<template>
  <card :title="$t('Color Management')">
    <vue-confirm-dialog></vue-confirm-dialog>
    <div style="display:flex;margin-bottom:6px;text-align:left" class="row">
    
      <p class="gf-item">Change theme colors</p>
      
      <div style="margin-top:32px;display:flex;text-align:left">
        <input type="color" style="min-width:50px;height:50px;margin-right:20px;" id="  " name="myBgColor" v-model="myBgColor" >
        <label for="myBgColor" class="typo__label gf-item">Background color</label>
      </div>

      <div style="margin-top:32px;display:flex;text-align:left">
        <input type="color" style="min-width:50px;height:50px;margin-right:20px;" id="myPrimaryColor" name="myPrimaryColor" v-model="myPrimaryColor" >
        <label for="myPrimaryColor" class="typo__label gf-item">Primary color</label>
      </div>

      <div style="margin-top:32px;display:flex;text-align:left">
        <input type="color" style="min-width:50px;height:50px;margin-right:20px;" id="mySecondaryColor" name="mySecondaryColor" v-model="mySecondaryColor" >
        <label for="mySecondaryColor" class="typo__label gf-item">Secondary color</label>
      </div>

      <div style="margin-top:32px;display:flex;text-align:left">
        <label class="btn btn-primary gf-button" style="margin-top:48px" v-on:click="onApply">Apply</label>
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
      myBgColor: '#000000',
      myPrimaryColor: '#000000',
      mySecondaryColor: '#000000',
    }
  },
  computed:{
    ...mapState({
      backgroundColor : state => state.project.backgroundColor,
      primaryColor : state => state.project.primaryColor,
      secondaryColor : state => state.project.secondaryColor,
    }),
  },

  async mounted () {
    debugger
    this.myBgColor = this.backgroundColor
    this.myPrimaryColor = this.primaryColor
    this.mySecondaryColor = this.secondaryColor

    if (this.myBgColor == null || this.myBgColor == undefined || this.myBgColor == '') {
      this.myBgColor = '#f0f000'
    }
    if (this.myPrimaryColor == null || this.myPrimaryColor == undefined || this.myPrimaryColor == '') {
      this.myPrimaryColor = '#0d6efd'
    }
    if (this.mySecondaryColor == null || this.mySecondaryColor == undefined || this.mySecondaryColor == '') {
      this.mySecondaryColor = '#00FF00'
    }

  },

  methods: {
    onApply(event) {
      let colors = {
        backgroundColor : this.myBgColor,
        primaryColor: this.myPrimaryColor,
        secondaryColor: this.mySecondaryColor
      }
      document.documentElement.style.setProperty('--background-color', this.myBgColor);
      document.documentElement.style.setProperty('--primary-color', this.myPrimaryColor);
      document.documentElement.style.setProperty('--secondary-color', this.mySecondaryColor);

      store.dispatch('project/saveThemeColors', colors)
      this.$router.replace({ name: 'home'})
    }
  }
}

</script>
