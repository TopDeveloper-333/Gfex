<template>
  <div>
    <p class="card-text" style="font-size: 2.4rem !important;text-align: center !important;"><u>WellHistory Data</u></p>
  </div>  
</template>

<script>
import store from '~/store'
import { mapState } from 'vuex'

export default {
  name: 'WellHistory',
  
  middleware: 'auth',
  
  props: {
    title: {type: String, default: null}
  },

  data() {
    return {

    }
  },

  computed: {
    ...mapState({

    })
  },

  methods: {

  },

  mounted() {
    
  }
}
</script>
