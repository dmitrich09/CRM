<template>
  <section>
      <div class="form-row justify-content-end">     
        <div class="col-4"></div> 
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th>#</th>
            <th>№ договора</th>
            <th>Дата  контакта</th> 
            <th>Город</th>
            <th>Cтатус</th>
            <th> Долг клиента </th>
            <th>Долг поставщику</th>
            <th>Дата оплаты</th>
            <th>Стоимость</th>
            <th>Маржа</th>
            <th>Дата пред. док.</th>
            <th>Дата план. закр</th>
            <th>Менеджер</th>
            <th>Комментарий	</th>
             <th>Организация</th>     
            <th>	</th>
           
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
              <th>{{ model.id}}</th>
              <td>{{model.numberagree}}</td>
              <td>{{inDateTime(model.contactdate)}}</td>
              <td>{{getCity(model.city_id).name}}</td>
              <td>{{getStatusOrk(model.state).name}}</td>
              <td>{{model.debt ? model.dept : 0 }}</td>
              <td>{{model.provider_debt ? model.provider_debt : 0}}</td>
              <td>{{ inDate(model.pay_date) }}</td>
              <td>{{ Number(model.total) - Number(model.our_cost) }}</td>
              <td>{{ model.total  }}</td>
              <td>{{ inDate(model.clientdoc_date) }}</td>
              <td>{{ inDate(model.close_date) }}</td>
              <td>{{getManager(model.manager_id).username}}</td>
              <td style="width: 20% " >{{model.comment}}<a href="#" @click="toComments(model.id)"><i :class="[model.deleted_at != null ? '' : 'fas fa-comment']" ></i></a></td>
              <td>
                  <a href="#" @click="toShow(model.client_id)">{{model.name}}</a>
              </td>
              <td>
                  <a href="#" @click="toPayment(model)"><img src="../../../static/images/iconsColors/card.svg" title="Оплата" style="max-width:20px"></a>      
              </td>
              <td>
                  <a  href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img src="../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px"></a>
              </td>   
              <td width="60px;">
                <a href="#">
                    <vue-confirmation-button
                        v-if="model.deleted_at == null || model.deleted_at == ''"
                        :css="'fui-trash'"
                        v-on:confirmation-success="deleteData(model.id)"  title="Удалить">
                    </vue-confirmation-button>
                </a>
              </td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" disabled></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder="№ договора"></td>
              <td><date-picker v-model="edit.contactdate"  valueType="format"  type="datetime" ></date-picker></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder="Город"></td>
              <td>
                 <select class="select"  v-model="edit.state" >
                    <option  v-for="status in statusOrk" :key="status.id" :value="status.id" >{{status.name}}</option>
                  </select>
              </td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><input type="text" class="form-control" v-model="edit.provider_debt" placeholder="Долг поставщику" style="width: 200px"></td>
              <td><date-picker v-model="edit.pay_date"  valueType="format"  ></date-picker></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder="Стоимость"></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder="Маржа "></td>
              <td><date-picker v-model="edit.clientdoc_date"  valueType="format"  ></date-picker></td>
              <td><date-picker v-model="edit.close_date"  valueType="format"  ></date-picker></td>
              
              <td>
                 <select class="select"  v-model="edit.manager_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              </td>
              <td>
                <select class="select"  v-model="edit.signagree" >
                     <option value="">Договор подписан</option>
                     <option value="10"> Да</option>
                      <option value="20"> Нет</option>
                  </select>
              </td>
              <td>
                <select class="select"  v-model="edit.signact" >  
                     <option value="">Акт подписан</option>
                     <option value="10"> Да</option>
                      <option value="20"> Нет</option>
                  </select>
              </td>
              <td><input type="text" class="form-control" v-model="edit.act_num" style="width: 100px" placeholder="Номер акта"></td>
              <td><a class="fui-check" @click="update"></a></td>
              <td><a class="fui-cross" @click="edit = null"></a></td>
          </tr>
        </tbody>
         <tr style="text-align: left">
              <th>Итого</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th style="width: 70px">{{getSumm('total') -getSumm('our_cost') }}</th>  
              <th style="width: 70px">{{getSumm('total')}}</th>
          
          </tr>
      </table>
  </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import dateFunc from '../../utils/dateFunc'
  import Vuex from 'vuex'
  import vueConfirmationButton from '../../utils/confirmButton'     
  import pagination from "../../utils/pagination"
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
        datalist: 'ork/datalist',
        pagination: 'ork/pagination',
        storeForm: 'mybuisness/form',
        citys: 'city/datalist',
        users: 'app/users',
        statusOrk:'dictionary/statusOrk'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          save: 'ork/save',
          find: 'ork/find',
          drop: 'ork/delete',
          findCity: 'city/findAll'
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
        this.storeForm.page =  this.page
        this.find(this.storeForm )
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
      getManager(id){
        var result = {}
        this.users.map((el) => {     
            if (id == el.id) {
                result = el
            }
        })
        return result
      },
      getStatusOrk(id){
        var result = {}
        this.statusOrk.map((el) => {
            if (id == el.id) {
                result = el
            }
        })
        return result
      },
      toComments(id){
           this.$store.commit('comments/setValComment', {object_id:id, type:60, component:'ork'}, {root: true})  
      },
      toShow(id){
         this.$router.push('../../../clients/show/' + id)
      },
      toPayment(model){
         var agreement_id = model.agreement_id
         var clients_id = model.clients_id
         this.$router.push('../../../ork/'+clients_id+'='+agreement_id)
      },
      getSumm(column){
        var summ = 0
         this.datalist.map(elem => {
           summ += +elem[column]
         })
         return summ
      }
    },
    created() {
      this.setHeader('ОРК')
      this.page = this.pagination.page
      this.findCity()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
