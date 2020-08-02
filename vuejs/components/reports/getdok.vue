<template>
  <section>
      <div class="row justify-content-center">
         <p style="font-size:24px">Реестр выданных документов</p>
      </div>  
      <div class="form-row" >
          <div class="col-2">
              <div style=" font-size: 70%;">Дата от</div> 
              <date-picker  v-model="form.date_from" valueType="format" type="datetime" ></date-picker>  
          </div>
           <div class="col-2">
              <div style=" font-size: 70%;">Дата до</div> 
              <date-picker  v-model="form.date_to" valueType="format" type="datetime" ></date-picker>  
          </div>
           <div class="col-2">
               <div style=" font-size: 70%;">Менеджер</div> 
              <select class="select"  v-model="form.manager_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
              </select>
          </div>
          <div class="col-2">
              <div style=" font-size: 70%;">Компания</div>
              <input type="text" class="form-control" v-model="form.name" >
          </div>
      </div>
       <div class="form-row" style="margin-top: 20px">
          <div class="col-2">
              <div style=" font-size: 70%;">Номер договора</div>
              <input type="text" class="form-control" v-model="form.agreenum" >
          </div>
            <div class="col-2">
              <div style=" font-size: 70%;">Название продукции</div>
              <input type="text" class="form-control" v-model="form.product" >
          </div>
            <div class="col-2">
              <div style=" font-size: 70%;">Номер акта</div>
              <input type="text" class="form-control" v-model="form.actnum" >
          </div>
      </div>
      <div class="form-row" style="margin-top: 20px">
            <div class="col-2">
              <div style=" font-size: 70%;">Орган</div>
              <input type="text" class="form-control" v-model="form.organ" >
          </div>
            <div class="col-2">
              <div style=" font-size: 70%;">Номер сертификата</div>
              <input type="text" class="form-control" v-model="form.sertnum" >
          </div>
          <div class="col" style="margin: auto" >
                  <button class="btn btn-success"  @click="saveData()">Сохранить</button>
                  <button class="btn btn-warning"  @click="form = {}">Отменить</button>
          </div>
      </div>
      
      <table class="table table-border table-hover" style="margin-top:30px">
        <tr>
            <th style="background-color:#d9d9fd">Компания</th>
            <th>Номер договора</th>
            <th class="firstColor" >Выданный документ</th>
            <th>Название продукции</th>
            <th class="firstColor">Менеджер ОРК</th>
            <th>Номер акта</th>
            <th class="firstColor">Акт подписан клиентом</th>
            <th>Договор подписан</th>
            <th class="firstColor">Выдан</th>
            <th>Действует до</th>
            <th class="firstColor">Месяц/год окончания</th>
            <th>Орган</th>
            <th class="firstColor">№ Сертификата</th>
            <th>Менеджер договора</th>
            <th></th>
            <th></th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id">
            <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']">
                  <td>{{model.planned_date}}</td>
                  <td>{{model.calls}}</td>
                  <td>{{model.leads}}</td>
                  <td>{{model.applications}}</td>
                  <td>{{model.kps}}</td>
                  <td>{{model.agreements}}</td> 
                  <td>{{model.pays}}</td>
                  <td>{{model.marges}}</td>
                  <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" ></a></td>
                  <td width="60px;"><vue-confirmation-button
                          v-if="model.deleted_at == null || model.deleted_at == ''"
                          :css="'fui-trash'"
                          v-on:confirmation-success="deleteData(model.id)">
                  </vue-confirmation-button></td>
                </tr>
                <tr v-if="edit != null && edit.id == model.id">
                    <td><input type="text" class="form-control" v-model="edit.id" placeholder="Название"></td>
                    <td><input type="text" class="form-control" v-model="edit.name" placeholder="Название"></td>  
                <td><a class="fui-check" @click="update"></a></td>
                  <td><a class="fui-cross" @click="edit = null"></a></td>
                </tr>
        </tbody>

      </table>
  </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
  import vueConfirmationButton from '../../utils/confirmButton'
  //import pagination from "../../utils/pagination"
   import DatePicker from 'vue2-datepicker'
  import 'vue2-datepicker/index.css'
  import 'vue2-datepicker/locale/ru'

  export default {
    mixins: [Acl],
    name: '',
    data () {
      return {
        form:{
          date_from: '',
          date_to: '',
        },
        search: null,
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      //pagination,
      DatePicker
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'plan/datalist',
        pagination: 'plan/pagination',
        users: 'app/users'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'plan/save',
        find: 'plan/find',
        drop: 'plan/delete',
        findUsers:'app/getUsers'
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
                .then(() =>{
                  this.findData()
                })
      },
      update() {
        this.setError(null)
        this.save(this.edit)
                .then(() =>{
                  this.edit = null
                  this.findData()
                })
      },
      deleteData(id){
        this.setError(null)
        this.drop(id)
                .then(() =>{
                  this.findData()
                })
      },
      toChange(model){
        this.edit = Object.assign({}, model)
      },
      setPage(i){
        this.page = i
        this.findData()
      },
      findData(){
        this.find()
      }
    },
    created() {
      this.setHeader('План')
      this.page = this.pagination.page
      this.findData()
      this.findUsers()
    }
  }
</script>
<style scope>
  .disabled {
    color:lightgrey;
  }
  .firstColor{
     background-color:#d9d9fd
  }
</style>
