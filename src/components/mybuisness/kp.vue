<template> 
  <section style="overflow-x: scroll">
      <div class="form-row justify-content-end">
         <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th>#</th>
            <th>Заявка</th>
            <th>Cтатус</th> 
            <th>Город</th>
            <th >Дата контакта</th> 
            <th>Менеджер</th>
            <th>Документы на руки</th>
            <th>ЛПР</th>
            <th>Др. предложение</th>
            <th>Для чего документ</th>
            <th>Комментарий</th>
            <th >Организация</th>
            <th>Продукция	</th>
            <th>Документ</th>
            <th>Итого</th>
             <th>Маржа</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
              <td>{{model.id}}</td>
              <td>{{model.application_id}}</td>
              <td>{{getStatus(model.state).name}}</td>
              <td>{{getCity(model.city_id).name}}</td>
              <td>{{inDate(model.contactdate)}}</td>
              <td>{{getManager(model.manager_id).username}}</td>
              <td>{{ model.doc_on_hand }}</td>
              <td>{{getLpr(model.is_lpr).name}}</td>
              <td>{{model.another_offer}}</td>
              <td>{{model.doc_for_what}}</td>
               <td>{{model.comment}}<a href="#" @click="toComments(model.id)"><i :class="[model.deleted_at != null ? '' : 'fas fa-comment']" ></i></a></td>
              <td><a href="#" @click="toShow(model.clients_id)">{{model.name}}</a></td>
              <td>??</td>
              <td>??</td>
              <td>{{model.total}}</td>
              <td>{{model.our_cost}}</td> 
              <td>
                  <a href="#"><img src="../../../static/images/iconsColors/user-interface.svg" title="Состав" style="max-width:20px"></a>
              </td>  
              <td>
                  <a :href="toPrintKp(model.id)"><img src="../../../static/images/iconsColors/printer.svg" title="Напечатать " style="max-width:20px"></a>
              </td>
              <td>
                  <a href="#" @click="toSendMail(model.id)"><img src="../../../static/images/iconsColors/mail.svg" title="Отправить" style="max-width:17px"></a>
              </td>
              <td>
                  <a href="#"><i class="fa fa-angle-double-right" title="Создать Договор" @click="toCreateСontract(model.id)"></i></a>
              </td>  
              <td >
                  <a  href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img src="../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px"></a>  
              </td>   
              <td width="60px;" >
                  <a href="#">
                      <vue-confirmation-button
                        v-if="model.deleted_at == null || model.deleted_at == ''"
                        :css="'fui-trash'"
                        v-on:confirmation-success="deleteData(model.id)"
                        title="Удалить">
                      </vue-confirmation-button>
                  </a>
              </td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" disabled="disabled" ></td>
              <td><input type="text" class="form-control" disabled="disabled" ></td>
              <td>
                  <select class="select"  v-model="edit.state" >
                     <option value="10"> Составлено</option>
                     <option value="20"> Отправлено</option>
                     <option value="30"> Ценовые переговоры по ПК</option>
                     <option value="40"> Договор</option>
                      <option value="50"> Отмена</option>
                  </select>
              </td>
              <td><input type="text" class="form-control"  disabled="disabled" ></td>
        
              <td><date-picker v-model="edit.contactdate"  valueType="format"  type="datetime"  ></date-picker></td>
              <td>
                 <select class="select"  v-model="edit.manager_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              </td>
             
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td ><input type="text" class="form-control" v-model="edit.client_name" style="width: 250px" ></td>
              <td><input type="text" class="form-control" disabled="disabled" ></td>
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
  import dateFunc from '../../utils/dateFunc'
  import DatePicker from 'vue2-datepicker'
  import 'vue2-datepicker/index.css'
  import 'vue2-datepicker/locale/ru'

  export default {
    mixins: [Acl, dateFunc],
    name: 'citypek',
    data () {
      return {
        form:{},
        search: null,
        page: null,
        edit: {client_name: 'Уважаемый(ая)'},
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
          datalist: 'kp/datalist',
          pagination: 'kp/pagination',
          storeForm: 'mybuisness/form',
          users: 'app/users',
          citys: 'city/datalist',
          statuses: 'dictionary/kpStatuses',
          is_lpr: 'dictionary/is_LprList'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'kp/save',
        find: 'kp/find',
        drop: 'kp/delete'
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
                .then(() =>{
                  this.find(this.storeForm)
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
        this.find(this.storeForm)
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
        this.statuses.map((el) => {
           if(id == el.id){
             result = el
           }
        })
        return result
      },
      getLpr(id){
        var result = {}
        this.is_lpr.map((el) => {
           if(id == el.id){
             result = el
           }
        })
        return result
      },
      toCreateСontract(id){
        this.$router.push('/create/createContract/'+ id)  
      },
      toComments(id){
         this.$store.commit('comments/setValComment', {object_id:id, type:40, component:'kp'}, {root: true})
      },
      toShow(id){
        this.$router.push('../../clients/show/' + id)   
      },
      toPrintKp(id){
        return "https://api.srcert.ru/index.php/kp/print?id="+ id   
      },
      toSendMail(){

      }
    },
  created() {
      this.setHeader('Коммерческие')
      this.page = this.pagination.page
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
