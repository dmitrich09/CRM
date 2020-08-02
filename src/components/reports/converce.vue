<template>
  <section style="font-size:18px">
      <div class="form-row" style="margin-top: 50px;font-size:16px">
          <div class="col">
              От<date-picker  v-model="form.from" valueType="format" ></date-picker> 
          </div>
          <div class="col">
              До<date-picker  v-model="form.to" valueType="format" ></date-picker>   
          </div>
          <div class="col">
              <button class="btn btn-primary"  @click="findData()" >Найти</button>
          </div>
          <div class="col-2"></div>
      </div>

    <h3>Общая</h3>
    <table class="table table-border table-hover" style="margin-top:30px">
        <tr>
            <th :style="firstColor">Месяц </th>
            <th :style="secondColor" >Конверсия заявка - оплата</th>
            <th :style="firstColor">Звонки</th>
            <th :style="secondColor">Конверсия</th>
            <th :style="firstColor">Заявки</th>
            <th :style="secondColor">Конверсия</th>
            <th :style="firstColor">КП</th>
            <th :style="secondColor">Конверсия</th>
            <th :style="firstColor">Договоры</th>
            <th :style="secondColor">Конверсия</th>
            <th :style="firstColor">Оплаты</th>
        </tr>
        <tbody v-for="(conrepDate, key) in conrepDates" :key="conrepDate.idx">    
            <tr >
                <th :style="firstColor">{{ key }}</th>
                <th :style="secondColor">{{ getApllsPays(key) }}</th>    
                <th :style="firstColor">{{  getCalls(key) }}</th>
                <th :style="secondColor">{{ getCallsApps(key) }}</th>   
                <th :style="firstColor"><a href="#"  @click="toApplDetals()" >{{ getAppls(key) }}</a></th>
                <th :style="secondColor">{{ getAppsKps(key) }}</th>
                <th :style="firstColor"><a href="#"  @click="toKpDetals()" >{{ getKps(key) }}</a></th>
                <th :style="secondColor">{{ getKpsAgreement(key) }}</th>
                <th :style="firstColor"><a href="#"  @click="toAgreemDetals()" >{{ getAgreement(key) }}</a></th>
                <th :style="secondColor">{{ getAgreementPays(key) }}</th>
                <th :style="firstColor"><a href="#"  @click="toPaymentDetals()" >{{ getPays(key) }}</a></th>
            </tr>     
       </tbody> 
       <tbody>
            <tr > 
                <th :style="firstColor">ИТОГО</th>   
                <th :style="secondColor">{{ getSummApllsPays() }}</th>   
                <th :style="firstColor">{{ getSummCalls() }}</th>   
                <th :style="secondColor">{{ getSummCallsApps() }}</th>   
                <th :style="firstColor"><a href="#"  @click="toApplDetals()" >{{ getSummAppls() }}</a></th>   
                <th :style="secondColor">{{ getSummApplsKps() }}</th>   
                <th :style="firstColor"><a href="#"  @click="toKpDetals()" >{{ getSummKps() }}</a></th>   
                <th :style="secondColor">{{ getSummKpsAgreement() }}</th>   
                <th :style="firstColor"><a href="#"  @click="toAgreemDetals()" >{{ getSummAgreement() }}</a></th>   
                <th :style="secondColor">{{ getSummAgreementPays() }}</th>   
                <th :style="firstColor"><a href="#"  @click="toPaymentDetals()" >{{ getSummPays() }}</a></th>   
            </tr>   
       </tbody>   
    </table>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-11">
            <table style="text-align: left;border-top:0" v-for="user in users" :key="user.idx" class="table table-border table-hover">
                <tbody  style="border-top:0" >      
                      <tr> 
                          <th @click="active = user.id "  style="border-top:0;">{{ user.username }}</th>  
                      </tr>
                </tbody>
                <tbody v-if="active == user.id">
                    <tr > 
                        <th :style="firstColor">Месяц </th>
                        <th :style="secondColor" >Конверсия заявка - оплата</th>
                        <th :style="firstColor">Звонки</th>
                        <th :style="secondColor">Конверсия</th>
                        <th :style="firstColor">Заявки</th>
                        <th :style="secondColor">Конверсия</th>
                        <th :style="firstColor">КП</th>
                        <th :style="secondColor">Конверсия</th>
                        <th :style="firstColor">Договоры</th>
                        <th :style="secondColor">Конверсия</th>
                        <th :style="firstColor">Оплаты</th> 
                        <th><button type="button" class="close" @click="active = null" aria-hidden="true">×</button></th>
                    </tr >
                    <tr v-for="(conrepDate,key) in conrepDates"  :key="conrepDate.idx" style="border:0;text-align: center">
                          <th :style="firstColor" style="text-align: left">{{ key }}</th>  
                          <th :style="secondColor">{{ getApplsPaysManag(key, user.id) }}</th>
                          <th :style="firstColor">{{ getCallsManag(key, user.id) }}</th>
                          <th :style="secondColor">{{ getCallsAppsManag(key, user.id) }}</th> 
                          <th :style="firstColor"><a href="#"  @click="toApplDetals()" >{{ getApplsManag(key, user.id)}}</a></th>  
                          <th :style="secondColor">{{ getAppsKpsManag(key, user.id)}}</th>  
                          <th :style="firstColor"><a href="#"  @click="toKpDetals()" >{{ getKpsManag(key, user.id)}}</a></th>   
                          <th :style="secondColor">{{ getAgremKpsManag(key, user.id)}}</th>  
                          <th :style="firstColor"><a href="#"  @click="toAgreemDetals()" >{{ getAgreementManag(key, user.id)}}</a></th>  
                          <th :style="secondColor">{{ getAgremPaysManag(key, user.id)}}</th>
                          <th :style="firstColor"><a href="#"  @click="toPaymentDetals()" >{{ getPaysManag(key, user.id)}}</a></th>
                    </tr>
                    <tr  v-if="active == user.id" style="text-align: center"> 
                            <th :style="firstColor" style="text-align: left" >ИТОГО</th>   
                            <th :style="secondColor" >{{ getSummApllsPaysUser() }}</th>   
                            <th :style="firstColor" >{{ getSummCallsUser(user.id) }}</th>   
                            <th :style="secondColor">{{ getSummCallsAppsUser() }}</th>   
                            <th :style="firstColor"><a href="#"  @click="toApplDetals()" >{{ getSummApplsUser(user.id) }}</a></th>   
                            <th :style="secondColor">{{ getSummApplsKpsUser() }}</th>   
                            <th :style="firstColor"><a href="#"  @click="toKpDetals()" >{{ getSummKpsUser(user.id) }}</a></th>   
                            <th :style="secondColor">{{ getSummKpsAgreementUser() }}</th>   
                            <th :style="firstColor"><a href="#"  @click="toAgreemDetals()" >{{ getSummAgreementUser(user.id) }}</a></th>   
                            <th :style="secondColor">{{ getSummAgreementPaysUser() }}</th>   
                            <th :style="firstColor"><a href="#"  @click="toPaymentDetals()" >{{ getSummPaysUser(user.id) }}</a></th>   
                    </tr>   
                  </tbody>  
            </table>
        </div>
        <div class="col-1"></div>
     </div>
  </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
  import DatePicker from 'vue2-datepicker'
  import 'vue2-datepicker/index.css'
  import 'vue2-datepicker/locale/ru'
  
  export default {
    mixins: [Acl],
    name: 'citypek',
    data () {
      return {
        form:{
          user_id: '',
          from: '2020-01-01',
          to: '2020-12-01',
        },
        firstColor:'background-color:#C6E2FF',
        secondColor:'background-color:#E0FFFF',
        active: null,
        keys: [],
        callsUser:null,
        appsUser:null,
        kpsUser:null,
        agreementsUser:null,
        paysUser:null,
        calls: null,
        apps: null,
        agreements: null,
        kps: null,
        pays: null,
        search: null,
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      DatePicker,
    },
    watch:{
      conreps(val){
          if(this.conreps != null ){
              var key = Object.keys(val.dates)
              this.keys = key
          }
       }
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        users:'app/users',
        user:'auth/user',
        datalist: 'reports/datalistConverce', 
        pagination: 'calls/pagination',
      }),
     
      conreps(){
         var obj = Object.assign({}, this.datalist)
         return obj.conrep
      },
      report(){
         var obj = Object.assign({}, this.datalist)
         return obj.report
      },
      conrepDates(){
        var obj = Object.assign({}, this.conreps)
        return obj.dates
      },
      now(){
        return new Date().getDate()+'.'+(new Date().getMonth()+1)+'.'+new Date().getFullYear()
      }
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          findConverce: 'reports/findConverce',
          findUsers:'app/getUsers'
      }),
      findData(){
        this.findConverce(this.form)
        this.findUsers()
      },
      changeUser(){
        var obj = Object.assign({}, this.user) 
        this.form.user_id = obj.id  
      },
      getApllsPays(key){
        //конверсия заявок к оплатам
          if(this.conreps['pays'][key] !=null && this.conreps['apps'][key]  !=null) {
              var res =  this.conreps['apps'][key]['count'] / this.conreps['pays'][key]['count'] 
              return res ? Number(res).toFixed(2) : 0
          }else{
            return 0
          }
      },
      getCalls(key){
          //звонки
          var res = this.conreps['calls'][key] ?  this.conreps['calls'][key]['count'] : 0
          return res 
      },
      getCallsApps(key){
          //конверсия звонков к заявкам
          if(this.conreps['calls'][key] !=null && this.conreps['apps'][key] !=null ) {
              var res =  this.conreps['calls'][key]['count'] / this.conreps['apps'][key]['count'] 
              return res ? Number(res).toFixed(2) : 0
          }else{
            return 0
          }
      },
      getAppls(key){
          //заявки
          return this.conreps['apps'][key] ? this.conreps['apps'][key]['count'] : 0
      },
      getAppsKps(key){
          //конверсия заявок к кп
          if(this.conreps['kps'][key] !=null && this.conreps['apps'][key] ) {
              var res = this.conreps['apps'][key]['count'] / this.conreps['kps'][key]['count'] 
              return res ? Number(res).toFixed(2) : 0
          }else{
            return 0
          }
      },
      getKps(key){
          //кп
          return this.conreps['kps'][key] ? this.conreps['kps'][key]['count'] : 0
      },
       getKpsAgreement(key){
            //конверсия кп к договорам
						if(this.conreps['kps'][key] !=null && this.conreps['agreements'][key] ) {
                var res =  this.conreps['kps'][key]['count'] / this.conreps['agreements'][key]['count'] 
                return res ? Number(res).toFixed(2) : 0
          }else{
            return 0
          }
      },
      getAgreement(key){
          //конверсия договора
          return this.conreps['agreements'][key] ? this.conreps['agreements'][key]['count'] : 0
      },
       getAgreementPays(key){
          //конверсия договоров к платежам
					if(this.conreps['pays'][key] !=null && this.conreps['agreements'][key]  ) {
              var res =  this.conreps['agreements'][key]['count'] / this.conreps['pays'][key]['count'] 
              return res ? Number(res).toFixed(2) : 0
          }else{
            return 0
          }
      },
       getPays(key){
          //платежей
          return this.conreps['pays'][key] ? this.conreps['pays'][key]['count'] : 0
      },
      //######ИТОГО  
      //конверсии  
      getSummApllsPays() {
         // итого конверсия заявки к оплате
          var res = 0
          if(this.pays != 0 && this.pays !=null && this.apps != 0 && this.apps !=null){  
              res = this.apps / this.pays
          }
           return Number(res).toFixed(4)       
      },
      getSummCallsApps() {
         // итого конверсия звонков к заявкам
          var res = 0
          if(this.apps != 0 && this.apps !=null && this.calls != 0 && this.calls !=null){  
              res = this.calls / this.apps
          }
           return Number(res).toFixed(4)       
      },
      getSummApplsKps() {
         // итого конверсия заявки к кп 
          var res = 0
          if(this.apps != 0 && this.apps !=null && this.kps != 0 && this.kps !=null ){  
             res = this.apps / this.kps 
          }
           return Number(res).toFixed(4)      
      },
      getSummKpsAgreement() {
         // итого конверсия  кп к договорам 
           var res = 0
          if(this.agreements != 0 && this.agreements !=null && this.kps != 0 && this.kps !=null ){  
             res = this.kps /this.agreements
          }
           return Number(res).toFixed(4)  
      },
      getSummAgreementPays() {
         // итого конверсия  договорам к платежам 
          var res = 0
          if(this.agreements != 0 && this.agreements !=null && this.pays != 0 && this.pays !=null ){  
             res = this.agreements /this.pays
          }
           return Number(res).toFixed(4)  
      },
      //звонки,договоры,заявки и т.д
      getSummCalls(){
          // итого звонки
          var  summ = 0
          for(var i=0; i< this.keys.length;i++){
              var key = String(this.keys[i])
              var res = this.conreps['calls'][key] ?  this.conreps['calls'][key]['count'] : 0
              if(Number(res) != null){
                summ += Number(res)
              }
          }
          this.calls = summ
          return summ
      },
      getSummAppls(){
        // итого заявки
          var  summ = 0
          for(var i=0; i< this.keys.length;i++){
              var key = String(this.keys[i])
              var res = this.conreps['apps'][key] ?  this.conreps['apps'][key]['count'] : 0
              if(Number(res) != null){
                summ += Number(res)
              }
          }
          this.apps = summ
          return summ
      },
      getSummKps(){
        // итого кп
          var  summ = 0
          for(var i=0; i< this.keys.length;i++){
              var key = String(this.keys[i])
              var res = this.conreps['kps'][key] ?  this.conreps['kps'][key]['count'] : 0
              if(Number(res) != null){
                summ += Number(res)
              }
          }
          this.kps = summ
          return summ
      },
       getSummAgreement(){
        // итого договора
          var  summ = 0
          for(var i=0; i< this.keys.length;i++){
              var key = String(this.keys[i])
              var res = this.conreps['agreements'][key] ?  this.conreps['agreements'][key]['count'] : 0
              if(Number(res) != null){
                summ += Number(res)
              }
          }
          this.agreements = summ
          return summ
      },
      getSummPays(){
        // итого договора
          var  summ = 0
          for(var i=0; i< this.keys.length;i++){
              var key = String(this.keys[i])
              var res = this.conreps['pays'][key] ?  this.conreps['pays'][key]['count'] : 0
              if(Number(res) != null){
                summ += Number(res)
              }
          }
          this.pays = summ
          return summ
      },
      //#######расчет по менеджерам    
      getApplsPaysManag(key, manager){
           //конверсия заявки к оплатам
          if(this.conreps['pays'][manager] !=null && this.conreps['apps'][manager] !=null  ) {
              var res = this.conreps['apps'][manager][key] / this.conreps['pays'][manager][key]
                return res ? Number(res).toFixed(2) : 0
          }else{
              return 0 
          }
			},  
      getCallsManag(key, manager){
          //звонки
           var res = this.conreps['calls'][manager] ? this.conreps['calls'][manager][key] : 0
           return res ? Number(res) : 0
      },
      getCallsAppsManag(key, manager){
          //конверсия звонков к заявкам
          if(this.conreps['calls'][manager] !=null && this.conreps['apps'][manager] !=null  ) {
                var res = this.conreps['apps'][manager][key] / this.conreps['kps'][manager][key]
                return res ? Number(res).toFixed(2) : 0
          }else{
              return 0 
          }
      },
      getApplsManag(key, manager){
          //заявки
          var res = this.conreps['apps'][manager] == null ? 0 : this.conreps['apps'][manager][key]  
          return res ? Number(res) : 0
      },
      getAppsKpsManag(key, manager){
           //конверсия заявок к кп
           if(this.conreps['apps'][manager] !=null && this.conreps['kps'][manager] !=null  ) {
                var res = this.conreps['apps'][manager][key] / this.conreps['kps'][manager][key]
                return res ? Number(res).toFixed(2) : 0
          }else{
              return 0 
          }
      },
      getKpsManag(key, manager){
          //кп
          var res = this.conreps['kps'][manager] == null ? 0 : this.conreps['kps'][manager][key]  
          return res ? Number(res) : 0
      },
      getAgremKpsManag(key, manager){
         //конверсия кп к договорам
           if(this.conreps['agreements'][manager] !=null && this.conreps['kps'][manager] !=null  ) {
                var res = this.conreps['kps'][manager][key] / this.conreps['agreements'][manager][key]
                return res ? Number(res).toFixed(2) : 0
          }else{
              return 0 
          }
      },
      getAgreementManag(key, manager){
         //конверсия договора
          var res = this.conreps['agreements'][manager] == null ? 0 : this.conreps['agreements'][manager][key]  
          return res ? Number(res) : 0
      },
      getAgremPaysManag(key, manager){
          //конверсия договоров к платежам
           if(this.conreps['agreements'][manager] !=null && this.conreps['pays'][manager] !=null  ) {
                var res = this.conreps['agreements'][manager][key] / this.conreps['pays'][manager][key]
                 return res ? Number(res).toFixed(2) : 0  
          }else{
              return 0 
          }
      },
       getPaysManag(key, manager){
          //платежей
          var res = this.conreps['pays'][manager] == null ? 0 : this.conreps['pays'][manager][key]  
           return res ? Number(res) : 0
      },
      //######ИТОГО  по менеджерам
       //звонки,договоры,заявки и т.д
      getSummCallsUser(manager){
          // итого звонки  по менеджерам
           this.callsUser = 0
          var  summ = 0
          if(this.conreps['calls'][manager] !=null && this.conreps['calls'][manager] != 0){
              for(var i=0; i< this.keys.length;i++){ 
                 var key = String(this.keys[i])
                  var res = this.conreps['calls'][manager][key] ?  this.conreps['calls'][manager][key] : 0
                  if(Number(res) != null){
                      summ += Number(res)
                  }
              }
              this.callsUser = summ
          }
          return summ 
      },
      getSummApplsUser(manager){
        // итого заявки  по менеджерам
          this.appsUser = 0
          var  summ = 0
          if(this.conreps['apps'][manager] !=null && this.conreps['apps'][manager] != 0){
              for(var i=0; i< this.keys.length;i++){ 
                 var key = String(this.keys[i])
                  var res = this.conreps['apps'][manager][key] ?  this.conreps['apps'][manager][key] : 0
                  if(Number(res) != null){
                      summ += Number(res)
                  }
              }
              this.appsUser = summ
          }
          return summ 
      },
      getSummKpsUser(manager){
        // итого кп  по менеджерам
         this.kpsUser = 0
         var  summ = 0
          if(this.conreps['kps'][manager] !=null && this.conreps['kps'][manager] != 0){
              for(var i=0; i< this.keys.length;i++){ 
                 var key = String(this.keys[i])
                  var res = this.conreps['kps'][manager][key] ?  this.conreps['kps'][manager][key] : 0
                  if(Number(res) != null){
                      summ += Number(res)
                  }
              }
              this.kpsUser = summ
          }
          return summ 
      },
       getSummAgreementUser(manager){
        // итого договора  по менеджерам
           this.agreementsUser = 0
           var  summ = 0
          if(this.conreps['agreements'][manager] !=null && this.conreps['agreements'][manager] != 0){
              for(var i=0; i< this.keys.length;i++){ 
                 var key = String(this.keys[i])
                  var res = this.conreps['agreements'][manager][key] ?  this.conreps['agreements'][manager][key] : 0
                  if(Number(res) != null){
                      summ += Number(res)
                  }
              }
              this.agreementsUser = summ
          }
          return summ
      },
      getSummPaysUser(manager){
        // итого договора  по менеджерам
         this.paysUser = 0
         var  summ = 0
          if(this.conreps['pays'][manager] !=null && this.conreps['pays'][manager] != 0){
              for(var i=0; i< this.keys.length;i++){ 
                 var key = String(this.keys[i])
                  var res = this.conreps['pays'][manager][key] ?  this.conreps['pays'][manager][key] : 0
                  if(Number(res) != null){
                      summ += Number(res)
                  }
              }
              this.paysUser = summ
          }
          return summ
      },
      //итого конверсии
      getSummApllsPaysUser() {
         // итого конверсия заявки к оплате
          var res = 0
          if(this.paysUser != 0 && this.paysUser !=null && this.appsUser != 0 && this.appsUser !=null){  
              res = this.appsUser / this.paysUser
          }
          return Number(res).toFixed(4)     
      },
      getSummCallsAppsUser() {
         // итого конверсия звонков к заявкам
          var res = 0
          if(this.appsUser != 0 && this.appsUser !=null && this.callsUser != 0 && this.callsUser !=null){  
              res = this.callsUser / this.appsUser
          }
          return Number(res).toFixed(4)      
      },
      getSummApplsKpsUser() {
         // итого конверсия заявки к кп 
          var res = 0
          if(this.appsUser != 0 && this.appsUser !=null && this.kpsUser != 0 && this.kpsUser !=null ){  
             res = this.appsUser / this.kpsUser 
          }
          return Number(res).toFixed(4)    
      },
      getSummKpsAgreementUser() {
         // итого конверсия  кп к договорам 
           var res = 0
          if(this.agreementsUser != 0 && this.agreementsUser !=null && this.kpsUser != 0 && this.kpsUser !=null ){  
             res = this.kpsUser /this.agreementsUser
          }
          return Number(res).toFixed(4)  
      },
      getSummAgreementPaysUser() {
         // итого конверсия  договорам к платежам 
          var res = 0
          if(this.agreementsUser != 0 && this.agreementsUser !=null && this.paysUser != 0 && this.paysUser !=null ){  
             res = this.agreementsUser /this.paysUser
          }
           return Number(res).toFixed(4)     
      },
      toApplDetals(){
        this.$router.push('/application/detals/')
      },
      toKpDetals(){
        this.$router.push('/kp/detals/')   
      },
       toAgreemDetals(){
        this.$router.push('/agreement/detals/')
      },
       toPaymentDetals(){
        this.$router.push('/payment/detals/')
      }
     
    
    },
    created() {
      this.setHeader('Конверсия')
      this.page = this.pagination.page  
      this.findData()
      this.changeUser()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;   
  }
</style>  
