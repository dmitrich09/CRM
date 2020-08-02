<template>
  <section>
    <div>
      <button class="btn btn-primary" @click="showForm = !showForm;getUserId()"  v-if="showButtonForm"> Добавить звонок</button>
    </div> 
    <div class="form-row"  v-if="showForm" style="margin: 10px 0 10px 0;">
            <div class="col-3"  style="text-align: left;">
              <div style=" font-size: 70%;">Дата контакта</div> 
              <date-picker  v-model="form.contactdate" valueType="format" ></date-picker>  
          </div>
           <div class="col-3"  style="text-align: left;">
              <div style=" font-size: 70%;">Дата начала</div> 
              <date-picker  v-model="form.startdate" valueType="format" ></date-picker>  
          </div>
          <div class="col-3">
              <div style=" font-size: 70%;">Комментарий</div> 
              <textarea type="text" class="form-control" v-model="form.comment" ></textarea>
          </div>
          <div class="col-3 " style="margin: auto" >
                  <button class="btn btn-success"  @click="saveData()">Сохранить</button>   
                  <button class="btn btn-warning"  @click="form = {};showForm=false ">Отменить</button> 
          </div>
    </div>
      <div class="form-row justify-content-end">
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th>Дата контакта</th> 
            <th>Комментарий</th>
            <th>Менеджер</th>
            <th>Организация</th>
            <th>Город</th>
            <th>Состояние</th>  
            <th>Дата</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
            <td>{{inDateTime(model.contactdate)}}</td>
            <td>{{model.comment}}<a href="#" @click="toComments(model.id)"><i :class="[model.deleted_at != null ? '' : 'fas fa-comment']"></i></a></td>
            <td>{{getManager(model.user_id).username}}</td>
            <td><a href="#" @click="toShow(model.clients_id)">{{model.name}}</a></td>
            <td>{{getCity(model.city_id).name}}</td>
            <td>{{getStatus(model.status)}}</td>
            <td>{{inDateTime(model.startdate)}}</td>
            <td ><a href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img src="../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px"></a></td> 
            <td><a href="#"><i class="fa fa-angle-double-right" title="Создать ЭК" @click="toCreateLead(model.id,model.clients_id )"></i></a></td> 
            <td width="60px;" title="Удалить" >
                <a href="#">
                <vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
                </vue-confirmation-button>
                </a>
            </td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><date-picker v-model="edit.contactdate"  valueType="format" type="datetime" ></date-picker></td>
              <td><input type="text" class="form-control " disabled="disabled" ></td>
              <td>
                  <select class="select"  v-model="edit.user_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              </td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
               <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
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
    name: 'outcall',
    props:['showButtonForm', 'clients_id'],
    data () {
      return {
        showForm: false,
        form:{
          clients_id: this.clients_id,  
          contactdate: '',
          startdate: '',
          manager_id: this.userId,
          comment: 'Hoвая',
          user_id: this.userId,
          status: 10,
          description: 'Hoвая'
        },
        search: null,
        page: null,
        edit: null,
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
        datalist: 'outcall/datalist',
        pagination: 'outcall/pagination',
        storeForm: 'mybuisness/form',
        clients: 'clients/datalist',
        users: 'app/users',
        user: 'auth/user',
        citys: 'city/datalist',
        callsStatus: 'dictionary/callsStatus'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
          setMessage: 'app/setMessage',
          setError: 'app/setError',
          setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          save: 'outcall/save',
          find: 'outcall/find',
          drop: 'outcall/delete'
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
                  this.find(this.storeForm)
                })
      },
      deleteData(id){
        this.setError(null)
        this.drop(id)
                .then(() =>{
                  this.find(this.storeForm)
                })
      },
      toChange(model){
        this.edit = Object.assign({}, model)
      },
      setPage(i){
        this.page = i
        this.storeForm.page = this.page 
        this.find(this.storeForm )
      },
      toComments(id){
          this.$store.commit('comments/setValComment', {object_id:id, type:30, component:'outcall'}, {root: true})
      },
       toShow(id){
         this.$router.push('../../clients/show/' + id)
      },
      toCreateLead(id, clients_id){
        this.$router.push('/create/createLead/' + id +'&&clients_id='+ clients_id)  
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
    getStatus(id){
      var result = {}
      this.callsStatus.map((el) => {
        if(id == el.id){
          result = el.name
        }
      })
      return result
    },
    getUserId(){
      var obj = Object.assign({}, this.user)
      this.form.manager_id = obj.id
      this.form.user_id = obj.id
    },
    },
    created() {
      this.setHeader('Звонки')
      this.page = this.pagination.page
      this.getUserId()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
