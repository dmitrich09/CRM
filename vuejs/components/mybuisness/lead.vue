<template>
  <section>
    <div>
      <button class="btn btn-primary" @click="showForm = !showForm"  v-if="showButtonForm"> Добавить ЭК</button>
    </div> 
    <div class="form-row"  v-if="showForm" style="margin: 10px 0 10px 0;">
          <div class="col-3"  style="padding: 0">
              <div style=" font-size: 70%;">Дата контакта</div> 
              <date-picker  v-model="form.contactdate" valueType="format" ></date-picker>  
          </div>
          <div class="col-3"  style="padding: 0">
              <div style=" font-size: 70%;">Источник</div> 
              <select class="select"  v-model="form.source_id" >
                  <option  v-for="(source, id) in sources" :key="id"  v-bind:value="source.id" >{{source.name}}</option>
              </select>
          </div>
          <div class="col-3">
              <div style=" font-size: 70%;">Описание задачи</div> 
              <input type="text" class="form-control" v-model="form.description" >
          </div>
          <div class="col-3" style="margin: auto" >
                  <button class="btn btn-success"  @click="saveData()">Сохранить</button>   
                  <button class="btn btn-warning"  @click="form = {};showForm=false ">Отменить</button> 
          </div>
    </div>
      <div class="form-row justify-content-end">
        <div class="col-4"></div>
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th>Дата контакта</th> 
            <th>Интерес</th>
            <th>Комментарий</th>
            <th>Менеджер</th>
            <th>Организация</th>
            <th>Город</th>
            <th>Состояние</th>
            <th>Источник</th>
            <th style="width: 200px">Дата</th>
            <th ></th>
            <th ></th>
            <th ></th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
            <td>{{inDateTime(model.startdate)}}</td>
            <td>{{model.interesttype}}</td>
              <td>{{model.comment}}<a href="#" @click="toComments(model.id)"><i :class="[model.deleted_at != null ? '' : 'fas fa-comment']"></i></a></td>
            <td>{{getManager(model.manager).username}}</td>
            <td><a href="#" @click="toShow(model.clients_id)">{{model.name}}</a></td>
            <td>{{getCity(model.city_id).name}}</td>
            <td>{{getStatus(model.state).name}}</td>
            <td>{{getSources(model.source_id).name}}</td>
            <td>{{model.contactdate}}</td>
          <td ><a  a href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img src="../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px"></a></td>
            <td><a href="#"><i class="fa fa-angle-double-right" title="Создать Заявку" @click="toCreateAppls(model.id)"></i></a></td> 
            <td width="60px;" title="Удалить" ><a href="#">
              <vue-confirmation-button 
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
              </vue-confirmation-button></a>
            </td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
             <td><date-picker v-model="edit.contactdate"  valueType="format"  type="datetime" ></date-picker></td>
              <td style="width: 200px" ><input type="text" class="form-control" v-model="edit.interesttype" placeholder="Интерес"></td>
              <td style="width: 200px" ><input type="text" class="form-control" v-model="edit.comment" placeholder="Комментарий"></td>
              <td>
                <select class="select"  v-model="edit.manager" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              </td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td>
                 <select class="select"  v-model="edit.state" >
                     <option value="10"> Не обработан</option>
                     <option value="20"> Не дозвонился</option>
                     <option value="30"> Не вышел на ЛПР</option>
                     <option value="40"> ЛПР</option>
                  </select>
              </td>
              <td>
                  <select class="select"  v-model="edit.source_id" >
                    <option  v-for="source in sources" :key="source.id" :value="source.id" >{{source.name}}</option>
                  </select>
              </td>
              <td><input type="text" class="form-control"  disabled="disabled"></td>
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
  import dateFunc from '../../utils/dateFunc'
 
 export default {
    mixins: [Acl,dateFunc],
    name: 'citypek',
    props:['showButtonForm', 'clients_id'],
    data () {
      return {
        showForm: false,
        form:{
          clients_id: this.clients_id, 
          task_date: 'Hoвая',
          manager: '',
          comment: 'Hoвая',
          status_id: 1,
          description: 'Hoвая',
          source_id: '',
          
        },
        search: null,
        page: null,
        edit: {
          state:'Не обработан'
        },
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
        datalist: 'lead/datalist',
        pagination: 'lead/pagination',
        storeForm: 'mybuisness/form',
        users: 'app/users',
        citys: 'city/datalist',
        sources: 'source/datalist',
        createLead: 'app/createLead',
        statusLead: 'dictionary/statusLead' 
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'lead/save',
        find: 'lead/find',
        drop: 'lead/delete',
        findSource: 'source/find'
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
                .then(() =>{
                  this.find(this.storeForm )
                })
      },
      update() {
        this.setError(null)
        this.save(this.edit)
                .then(() =>{
                  this.edit = null
                  this.find(this.storeForm )
                })
      },
      deleteData(id){
        this.setError(null)
        this.drop(id)
                .then(() =>{
                  this.find(this.storeForm )
                })
      },
      toChange(model){
        this.edit = Object.assign({}, model)
      },
      setPage(i){
        this.page = i
        this.storeForm.page = this.page
        this.find(this.storeForm)
      },
      toCreateAppls(id){
        this.$router.push('/create/createAppls/'+ id)  
      },
      getManager(id){
            var result = {}
            this.users.map((el) => {
            if (id == el.id) {
                  result = el
                }
            })
            return result
      },
      getCity(id){
            var result = {}
            this.citys.map((el) => {
            if (id == el.id) {
                  result = el
                }
            })
            return result
      },
      getSources(id){
            var result = {}
            this.sources.map((el) => {
            if (id == el.id) {
                  result = el
                }
            })
            return result
      },
      toComments(id){
          this.$store.commit('comments/setValComment', { object_id:id, type:10, component:'lead'}, {root: true})
      },
      toShow(id){
         this.$router.push('../../clients/show/' + id)
      },
       getStatus(id){
        var result = {}
        this.statusLead.map((el) => {
          if(id == el.id){
            result = el
          }
        })
        return result
    }
    },
    created() {
      this.setHeader('ЭК')
      this.page = this.pagination.page
      this.findSource()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
