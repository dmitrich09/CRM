<template>
  <div id="app">
    <div class="container-fluid">
        <errormessage class="col-8 offset-2 mt-1 absolutealert"></errormessage>
      <div class="row">
        <div class="col padding_none" style="padding-left:0;padding-right:0;">
          <printBill v-if="printBillShow" :id="printId" ></printBill>
          <printContract v-if="printContractShow" :id="printIdContr" ></printContract>
          <navbar v-if="isAuthenticated"/>
          <div class="container-fluid">
            <router-view v-if="show" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from './components/admin/navbar'
import Vuex from 'vuex'
import errormessage from "./components/errormessage"
import printBill from "./components/print/printBill"
import printContract from "./components/print/printContract"

export default {
  name: 'app',
  data () {
      return {
        name: 'app',
        printBillShow: false,
        printId: null,
        printIdContr: null,
        printContractShow: false,
        show: true
      }
    },
  components: {
    Navbar,
    errormessage,
    printBill ,  
    printContract   
  },
   watch:{
        printBill: function(val){
            this.printId = val
            this.printBillShow = true
            this.printIdContr = false
            this.show = false
        },
        printContract: function(val){
            this.printIdContr = val
            this.printContractShow = true
            this.printId = false
            this.show = false
        }
    },
  computed: {
    ...Vuex.mapGetters({
      isAuthenticatedStore: 'auth/isAuthenticated',
      printBillStore: 'app/printBill',
      printContractStore: 'app/printContract',
    }),
      printBill(){
            return  Object.assign({}, this.printBillStore)   
      },
      printContract(){
            return  Object.assign({}, this.printContractStore)   
      },
      isAuthenticated(){
            if(this.printId != '' && this.printId != null){
                return false
            }
            if(this.printIdContr != '' && this.printIdContr != null){
                return false
            }
            return  Object.assign({}, this.isAuthenticatedStore)   
      }
  },
  methods: {
    check () {
      this.$store.dispatch('auth/check')
    }
  },
  created () {
    this.$store.dispatch('app/setDictionary')
  }
}
</script>

<style>


#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
    .absolutealert{
        position: absolute;
        z-index: 1000;
    }

</style>

