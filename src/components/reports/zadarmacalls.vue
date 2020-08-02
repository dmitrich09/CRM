<template>
  <section>
     <div class="form-row" style="margin-top:30px">
          <div class="col">
              <div style="font-size:12px">От</div>
              <date-picker  v-model="form.from" valueType="format" ></date-picker> 
          </div>
          <div class="col">
              <div style="font-size:12px">До</div>
              <date-picker  v-model="form.to" valueType="format" ></date-picker>   
          </div>
           <div class="col">
               <div style="font-size:12px">Тип звонка</div>
              <select class="select"  v-model="form.typecold" >
                    <option value="0" >Холодный</option>
                    <option value="1" >Теплый</option>
              </select>
          </div>
           <div class="col">
             <div style="font-size:12px">Тип звонка</div>
              <select class="select"  v-model="form.type" >
                    <option value="1" >Входящий</option>
                    <option value="0" >Исходящий</option>
              </select>
          </div>
          <div class="col">
              <div style="font-size:12px">Менеджер</div>
              <select class="select"  v-model="form.user_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
              </select>
          </div>
           <div  style="margin-top:20px">
             <input type="text" class="form-control" v-model="form.phone" placeholder="Номер телефона"> 
          </div>
          <div class="col">
              <button class="btn btn-primary" @click="findData() " v-if="!showForm">Обновить</button>
          </div>
          <div class="col-2"></div>
          <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:30px">
        <tr>
            <th>Наш id</th>
            <th>id zadarma</th>
            <th>Дата</th>
            <th>Откуда</th>
            <th>Куда</th>
            <th>Длительность</th>
            <th>Тип</th>
            <th>Тип</th>
            <th>Оператор</th>
            <th>Клиент</th>
            <th>Прослушать</th>
            <th></th>
            <th></th>
        </tr>
        <tbody v-for="model in callreport" :key="model.id">
            <tr   >
                   <td >{{model.id}}</td>
                   <td >{{model.zad_call_id}}</td>
                   <td >{{model.callstart}}</td>
                   <td >{{model.sip}}</td>
                   <td >{{model.destination}}</td>
                   <td >{{model.seconds}}</td>
                   <td >{{model.incoming == 0 ? 'Исходящий' : 'Входящий' }}</td> 
                   <td >{{model.is_warm == 1 ? 'Теплый' : 'Холодный' }}</td>
                   <td >{{getManager(model.user_id).username ? getManager(model.user_id).username : "не задано" }}</td>
                   <td ><a href="#" @click="toShow(model.clients_id)">{{model.name}}</a></td>
                  <td v-if="model.is_recorded" ><audio src="/calls/" preload="auto" controls></audio></td>
                  <td v-else>Звонок не был записан на АТС</td> 
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
          from: '',
          to: '',
          user_id: '',
          type:'',
          typecold:''
        },
        search: null,
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      pagination,
      DatePicker
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'calls/zadarmacalls',
        users:'app/users',
        user:'auth/user',
        pagination: 'plan/pagination',
        fromCallreport:'reports/formFromCallreport'
      }),
      query(){
         var obj = Object.assign({}, this.datalist)
         return obj.query
      },
      callreport(){
         var obj = Object.assign({}, this.datalist)
         return obj.callreport
      },
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          findUsers: 'app/getUsers',
          getZadarmacalls: 'calls/getZadarmacalls',  
      }),
      setPage(i){
        this.page = i
        this.findData()
      },
      findData(){
        if(this.fromCallreport !=null){
            this.form.from = this.fromCallreport.from
            this.form.to = this.fromCallreport.to
            this.form.user_id = this.fromCallreport.user_id
            this.form.type = this.fromCallreport.type
            this.form.typecold = this.fromCallreport.typecold
            this.form.allrep = this.fromCallreport.allrep
        }
        this.getZadarmacalls(this.form)
        
      },
      toShow(id){
         this.$router.push('../../clients/show/' + id)
      },
      changeUser(){
        var obj = Object.assign({}, this.user) 
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
    },
    created() {
      this.setHeader('Звонки задарма -в запросе стоит лимит 100!!')
      this.page = this.pagination.page
      this.findData()  
      this.findUsers()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
