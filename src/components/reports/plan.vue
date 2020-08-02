<template>
  <section>
      <div class="row justify-content-center">
         <p style="font-size:24px">План</p>
      </div>  
      <div class="form-row">
          <div class="col-3">
            <input class="form-control" @keyup="findData()" @change="findData()"  v-if="!showForm" v-model="search" type="text">
          </div>
          <div class="col-1">
            <button class="btn btn-primary" @click="saveData() " v-if="!showForm">Найти</button>
          </div>
          <div class="col-2"></div>
           <div class="col-2">
            <button class="btn btn-primary" @click="showForm = !showForm">Добавить</button>
          </div>
          <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <div class="form-row"  v-if="showForm" style="margin: 10px 0 10px 0;">    
                    <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <date-picker  v-model="form.planned_date" valueType="format" ></date-picker>  
                    </div>
                      <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <input type="text" class="form-control" v-model="form.calls" placeholder="Звонки"> 
                    </div>
                     <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <input type="text" class="form-control" v-model="form.leads" placeholder="ЭК"> 
                    </div>
                     <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <input type="text" class="form-control" v-model="form.applications" placeholder="Заявки"> 
                    </div>
                     <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <input type="text" class="form-control" v-model="form.kps" placeholder="КП"> 
                    </div>
                     <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <input type="text" class="form-control" v-model="form.agreements" placeholder="Договоры"> 
                    </div>
                      <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <input type="text" class="form-control" v-model="form.pays" placeholder="Оплат"> 
                    </div>
                      <div class="col-2"  style="text-align: left;">
                        <div style=" font-size: 70%;"></div> 
                        <input type="text" class="form-control" v-model="form.marges" placeholder="Маржи"> 
                    </div>
                    <div class="col" style="margin: auto" >
                            <button class="btn btn-success"  @click="saveData()">Сохранить</button>   
                            <button class="btn btn-warning"  @click="form = {};showForm=false ">Отменить</button> 
                    </div>
                    
                </div>
      <table class="table table-border table-hover" style="margin-top:30px">
        <tr>
            <th>Дата</th>
            <th>Звонки</th>
            <th>ЭК</th>
            <th>Заявки</th>
            <th>КП</th>
            <th>Договоры</th>
            <th>Оплат</th>
            <th>Маржи</th>
            <th>Удалить</th>
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
          name: '',
        },
        search: null,
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      pagination,
      DatePicker
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'plan/datalist',
        pagination: 'plan/pagination',
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
        drop: 'plan/delete'
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
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
