<template>
  <section>{{  }}
      <div class="form-row" style="margin-top: 50px;">
          <div class="col">
              От<date-picker  v-model="form.from" valueType="format" ></date-picker> 
          </div>
          <div class="col">
              До<date-picker  v-model="form.to" valueType="format" ></date-picker>   
          </div>
          <div class="col">
              <button class="btn btn-primary" @click="findData() " v-if="!showForm">Найти</button>
          </div>
          <div class="col-2"></div>
          <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
   
      <table class="table table-border table-hover" style="margin-top:30px">
        <tr>
            <th>Номер договора</th>
            <th>Менеджер</th>
            <th>Компания</th>
            <th>Сумма договора</th>
            <th>Себестоимость</th>
            <th>Маржа</th>
            <th>Дата оплаты</th>  
            <th>Сумма оплаты</th>
            <th>Долг клиента</th>
            <th>Долг поставщику</th>
            <th>Поставщики</th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id" > 
            <tr >   
                <td>{{model.numberagree }} </td>
                <td>{{getManager(model.manager_id).username }} </td>
                <td>{{model.name }} </td>
                <td>{{model.total }} </td>
                <td>{{model.our_cost }} </td>
                <td>{{ Number(model.total) - Number(model.our_cost) ? Number(model.total) - Number(model.our_cost) : 0}} </td>
                <td>{{model.payment_date }} </td>
                <td>{{model.amount }} </td>
                <td>{{model.debt }} </td>
                <td>{{model.provider_debt == null ? model.our_cost : model.provider_debt }} </td>
                <td>{{model.maxid }} </td>
            </tr>
          </tbody> 
          <tbody> 
              <tr>
                  <td><b>ИТОГО</b></td>
                  <td></td>
                  <td></td>
                  <td><b>{{ getSumTotal('total') }}</b></td>
                  <td><b>{{ getSumTotal('our_cost') }}</b></td>
                  <td><b>{{ getSumTotal('our_cost_total') }}</b></td>
                    <td></td>
                  <td><b>{{ getSumTotal('amount') }}</b></td>
                  <td><b>{{ getSumTotal('debt') }}</b></td>
                  <td><b>{{ getSumTotal('provider_debt') }}</b></td>
              </tr>
         </tbody>
      </table>
  </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
  import pagination from "../../utils/pagination"
   import DatePicker from 'vue2-datepicker'
  import 'vue2-datepicker/index.css'
  import 'vue2-datepicker/locale/ru'

  export default {
    mixins: [Acl],
    name: 'citypek',
    data () {
      return {
        form:{
            to: '2020-06-01',
            from: '2020-05-21',
            user_id:'',
            type:''
        },
        search: null,
        page: null,
        edit: null,
        showForm: false,
      }
    },
    components: {
      pagination,
      DatePicker
    },
    watch:{
     
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'reports/datalistPayrep',
        user:'auth/user',
        users:'app/users',
        pagination: 'calls/pagination',
      }),
      report(){
           return Object.assign({}, this.datalist)
      },
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          findPayrep: 'reports/findPayrep',
          getUsers: 'app/getUsers',
      }),
      setPage(i){
        this.page = i
        this.findData()
      },
      findData(){
        this.findPayrep(this.form)
      },
      toZadarmaCalls(){
        this.$router.push('./zadarmacalls/')
      },
      changeUser(){
        var obj = Object.assign({}, this.user ) 
        this.form.user_id = obj.id  
      },
      getManager(id){
          var result = {}
          this.users.map((el) => {
            if(id == el.id){
              result = el
            }
          })
          return result
      },
      getSumTotal(val){
          var sum = 0
          var sumOur_cost = 0
          var sumTotal = 0
          if(val == 'our_cost_total'){
              for(var e = 0;e < Object.keys(this.report).length; e++){
                  sumOur_cost += Number(this.report[e]['our_cost'])
                  sumTotal += Number(this.report[e]['total'] )
                  sum = sumTotal - sumOur_cost
              }
              val = 0
          } 
          if(this.report != 0 && this.report != null && val != null && val != 0){
              for(var i = 0;i < Object.keys(this.report).length; i++){
                 sum += Number(this.report[i][val])
              }
          } 
          return sum
      }
    },
    created() {
      this.setHeader('Отчеты о выплатах')
      this.page = this.pagination.page
      this.findData()
      this.getUsers()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
