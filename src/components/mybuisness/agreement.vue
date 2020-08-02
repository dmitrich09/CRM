<template>
  <section style="overflow-x: scroll">
      <div class="form-row justify-content-end">
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
             <th>#</th>
            <th>Cтатус</th>   
            <th>Дата контакта</th>
            <th>Город</th>
            <th>Менеджер</th>
            <th>Комментарий</th>
            <th>Организация</th>
            <th>Продукция	</th>
            <th>Документ</th>
            <th>Итого</th>
            <th>Маржа</th>
            <th></th>
            <th></th>
            <th></th>
            <td v-if="edit != null "><b>Дата договора </b></td>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left"> 
              <td>{{model.numberagree}}</td>
              <td>{{getStatusAgr(model.state).name}}</td>
              <td>{{inDate(model.contactdate)}}</td>
              <td>{{getCity(model.city_id).name}}</td>
              <td>{{getManager(model.manager_id).username}}</td>
              <td>{{model.comment}}<a href="#" @click="toComments(model.id)"><i :class="[model.deleted_at != null ? '' : 'fas fa-comment']" ></i></a></td>
              <td><a href="#" @click="toShow(model.clients_id)">{{model.name}}</a></td>
              <td>{{ model.document_id }}</td>  
              <td>{{ model.document_id }}</td> 
              <td>{{model.total}}</td>
              <td>{{model.our_cost}}</td>   
              <td></td>
              <td>
                   <a :href="toPrintBill(model.id)"><img src="../../../static/images/iconsColors/user-interface.svg" title="Состав" style="max-width:20px;"></a>   
              <td>
                    <a @click="toPayment(model)"><img src="../../../static/images/iconsColors/card.svg" title="Оплата" style="max-width:20px;margin-left:7px"></a>            
              </td>
               <td>
                    <a :href="toPrintBill(model.id)"><img src="../../../static/images/iconsColors/printer.svg" title="Напечатать счет" style="max-width:20px;"></a>
              </td>
              <td>
                    <a :href="toPrintBillStamp(model.id)"><img src="../../../static/images/iconsColors/printer.svg" title="Напечатать счет с печатью" style="max-width:20px; margin-left:7px"></a>
              </td>
              <td>  
                  <a  href="#" @click="toPrintBillStamp(model.id)" ><img src="../../../static/images/iconsColors/mail.svg" title="Отправить счет" style="max-width:20px; margin-left:7px"></a>
              </td>
              <td>
                  <a href="#" @click="toPrintContract(model.id)"><img src="../../../static/images/iconsColors/printer.svg" title="Напечатать договор" style="max-width:20px; margin-left:7px"></a>
              </td>
               <td>  
                  <a href="#" @click="toPrintContract(model.id)"><img src="../../../static/images/iconsColors/printer.svg" title="Напечатать договор подписанный" style="max-width:20px; margin-left:7px"></a>
              </td>
              <td>
                  <a href="#" @click="toPrintContract(model.id)"><img src="../../../static/images/iconsColors/mail.svg" title="Отправить договор " style="max-width:20px; margin-left:7px"></a>
              </td>
              <td>
                  <a href="#" @click="toPrintContract(model.id)"><img src="../../../static/images/iconsColors/mail.svg" title="Отправить договор подписанный" style="max-width:20px; margin-left:7px"></a>
              </td>
               <td>
                  <a href="#"><i class="fa fa-angle-double-right" title="Создать ОРК" @click="toCreateOrk(model.id)" style="max-width:20px; margin-left:7px"></i></a>
                </td>
               <td>
                  <a  href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img src="../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px; margin-left:7px"></a>
              </td>
               <td>
                  <a href="#" style="max-width:20px; margin-left:7px"> 
                    <vue-confirmation-button
                        title="Удалить"
                        v-if="model.deleted_at == null || model.deleted_at == ''"
                        :css="'fui-trash'"
                        v-on:confirmation-success="deleteData(model.id)">
                    </vue-confirmation-button>
                  </a>
              </td>
              
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" disabled="disabled" placeholder="#"></td>
              <td>
                  <select class="select"  v-model="edit.state" >
                    <option  v-for="status in statusAgr" :key="status.id" :value="status.id" >{{status.name}}</option>
                  </select>
              </td>
              <td><date-picker v-model="edit.contactdate"  valueType="format"  type="datetime"  ></date-picker></td>
              <td><input type="text" class="form-control"  disabled="disabled" placeholder=""></td>
                <td>
                 <select class="select"  v-model="edit.manager_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              </td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder=" "></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder=""></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder=" "></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder=" "></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder=" "></td>
              <td><input type="text" class="form-control" disabled="disabled" placeholder=" " ></td>
              <td><input type="text" class="form-control" v-model="edit.person" placeholder="В лице" style="width: 200px"></td>
              <td><input type="text" class="form-control" v-model="edit.short_person" placeholder="Подпись " style="width: 200px"></td> 
              <td>
                  <select class="select"  v-model="edit.tax" >
                    <option  value="10">ООО Современные решения</option>
                    <option  value="20">ИП Филимонов</option>
                  </select>
              </td>
              <td><date-picker v-model="edit.agreedate"  valueType="format"  ></date-picker></td>
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
              <th></th>
              <th style="width: 70px" >{{getSumm('total')}}</th>
              <th style="width: 70px">{{getSumm('our_cost')}}</th>
             
          </tr>
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
  import '../../utils/dateFunc'
  import dateFunc from '../../utils/dateFunc'
 
  export default {
    mixins: [Acl,dateFunc],
    name: 'agreement',
    data () {
      return {
        form:{},
        search: null,
        page: null,
        edit: null,
        keys:[]
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
          datalist: 'agreement/datalist',
          pagination: 'agreement/pagination',  
          storeForm: 'mybuisness/form',
          citys: 'city/datalist',
          users: 'app/users',
          is_lpr:'dictionary/is_LprList',
          statusAgr:'dictionary/statusAgreement'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          save: 'agreement/save',
          find: 'agreement/find',
          drop: 'agreement/delete',
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
      getStatusAgr(id){
        var result = {}
        this.statusAgr.map((el) => {
           if(id == el.id){
             result = el
           }
        })
        return result
      },
      toCreateOrk(id){
          this.$router.push('/create/createOrk/'+ id)   
      },
      toPrintBill(id){
        return "https://api.srcert.ru/index.php/agreement/bill?id="+ id  
      },
      toPrintBillStamp(id){
        return "https://api.srcert.ru/index.php/agreement/bill?id="+ id +"&stamp=1"   
      },
       toPrintContract(){
        
      },
      toComments(id){
          this.$store.commit('comments/setValComment', {object_id:id, type:50,  component:'agreement'}, {root: true})
      },
      toShow(id){
         this.$router.push('../../clients/show/' + id)
      },
      toPayment(model){
         var agreement_id = model.agreement_id
         var clients_id = model.clients_id
         this.$router.push('../../../ork/'+clients_id+'='+agreement_id)
      },
      getSumm(column){
          var keys = Object.keys(this.datalist)
          var summ = 0
          for(var i=0; i< keys.length;i++){
              var key = String(keys[i])
              var res = this.datalist[key][column] ?  this.datalist[key][column] : 0
              summ += Number(res)
          }
          var result = String(summ)
          return result.replace(/(\d)(?=(\d{3})+(\D|$))/g, '$1 ') 
      }
    },
    created() {
      this.setHeader('Договор')
      this.page = this.pagination.page
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
