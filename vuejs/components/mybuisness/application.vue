<template>
  <section>
      <div class="form-row justify-content-end">
      <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th></th>
            <th>№</th>
            <th>Город</th>
            <th>Дата контакта</th> 
            <th>Менеджер</th>
            <th>Организация</th>
            <th>Продукция</th>
            <th>Документ</th>
            <th>Рассчитывает</th>
            <th>Комментарий</th>
            <th>Документы на руки	</th>
            <th>ЛПР</th>
            <th>Др. предложение	</th>
            <th>Для чего документ</th>
            <th>Итого</th>
            <th>Маржа</th>
            <th>Источник</th>
            <th></th>
            <th></th>
            <th></th>
            <td v-if="edit != null "><b>ОКП</b></td> 
            <td v-if="edit != null "><b>ОКПД2</b></td>
            <td v-if="edit != null "><b>ТНВЭД</b></td>
            <td v-if="edit != null "><b>По какому документу выпуск</b></td>  
            <td v-if="edit != null "><b>Область применения</b></td>
            <td v-if="edit != null "><b>Страна изготовитель</b></td>  
            <td v-if="edit != null "><b>Страна заявитель</b></td>  
            <td v-if="edit != null "><b>Выезд</b></td>
            <td v-if="edit != null "><b>Реальные испытания</b></td>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
              <td><a href="#"><img src="../../../static/images/iconsColors/eye.svg" style="max-width:20px" @click="showAppls(model.id)"></a></td>   
              <td>{{ model.id }}</td>
              <td>{{ getCity(model.city_id).name }}</td>
              <td>{{inDate(model.startdate)}}</td>
              <td>{{ getManager(model.manager_id).username }}</td>
              <td><a href="#" @click="toShow(model.clients_id)">{{model.name}}</a></td>
              <td>{{model.nameproduct}}</td>
              <td>{{model.documentprod}}</td>
              <td>{{ getManager(model.countmanager_id).username }}</td>
              <td>{{model.comment}}<a href="#" @click="toComments(model.id)"><i :class="[model.deleted_at != null ? '' : 'fas fa-comment']" ></i></a></td>
              <td>{{model.doc_on_hand}}</td>
              <td>{{model.is_lpr}}</td>
              <td>{{model.another_offer}}</td>
              <td>{{model.doc_for_what}}</td>
              <td>{{model.total}}</td>
              <td>{{model.our_cost}}</td>
              <td>{{getSource(model.source_id).name}}</td>
               <td ><a a href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img src="../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px"></a></td> 
               <td><a href="#"><i class="fa fa-angle-double-right" title="Создать Коммерческое" @click="toCreateKp(model.id)"></i></a></td>  
              <td width="60px;" title="Удалить"><a href="#"><vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
            </vue-confirmation-button></a></td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" disabled="disabled"></td>
               <td><input type="text" class="form-control" disabled="disabled"></td>
               <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><date-picker v-model="edit.startdate"  valueType="format" type="datetime" ></date-picker></td>
              <td>
                <select class="select"  v-model="edit.manager_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              </td>
              <td> <input type="text" class="form-control" disabled="disabled"></td>
               <td><input type="text" class="form-control" disabled="disabled"></td>
                <td><input type="text" class="form-control" disabled="disabled"></td>
              <td>
                 <select class="select"  v-model="edit.countmanager_id" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              </td>
              <td><input type="text" class="form-control" disabled="disabled"></td>
              <td><date-picker v-model="edit.doc_on_hand"  valueType="format"  ></date-picker></td>
              <td>
                  <select  class="select"  v-model="form.is_lpr" >
                      <option value=""> ЛПР</option>
                    <option value="10"> Да</option>
                    <option value="20">Нет</option>
                  </select> 
              </td>
              <td  ><input type="text" class="form-control" v-model="edit.another_offer" placeholder="Другие предложения " style="width: 200px">
              <td ><input type="text" class="form-control" v-model="edit.doc_for_what" placeholder="Для чего докуменt " style="width: 200px">
              <td><input type="text" class="form-control"  disabled="disabled" ></td>
              <td><input type="text" class="form-control"  disabled="disabled"></td>
              <td><input type="text" class="form-control"  disabled="disabled"></td>
              <td><input type="text" class="form-control"  disabled="disabled"></td>
         
              <td><a class="fui-check" @click="update"></a></td>
              <td><a class="fui-cross" @click="edit = null"></a></td>  
              <td><input type="text" class="form-control" v-model="edit.okp" placeholder="ОКП" style="width: 200px"></td> 
              <td><input type="text" class="form-control" v-model="edit.okpd2" placeholder="ОКПД2" style="width: 200px"></td>
              <td><input type="text" class="form-control" v-model="edit.tnved" placeholder="ТНВЭД" style="width: 200px"></td>
              <td><input type="text" class="form-control" v-model="edit.documentprod" placeholder="По какому документу выпуск" style="width: 200px"></td>
              <td><input type="text" class="form-control" v-model="edit.field" placeholder="Область применения" style="width: 200px"></td>
              <td><input type="text" class="form-control" v-model="edit.countrymade" placeholder="Страна изготовитель" style="width: 200px"></td>
              <td><input type="text" class="form-control" v-model="edit.countryask" placeholder="Страна заявитель" style="width: 200px"></td>
              <td>
                 <select class="select"  v-model="edit.exittoclient" >
                    <option  v-for="elem in yesNoList" :key="elem.id" :value="elem.id" >{{elem.name}}</option>
                  </select>
              </td>
              <td>
                  <select class="select"  v-model="edit.test" >
                    <option  v-for="elem in yesNoList" :key="elem.id" :value="elem.id" >{{elem.name}}</option>
                  </select>
              </td>
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
        datalist: 'application/datalist',
        pagination: 'application/pagination',
        storeForm: 'mybuisness/form',
        citys: 'city/datalist',
        users: 'app/users',
        sources: 'source/datalist',
        yesNoList: 'dictionary/yesNoList'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
          setMessage: 'app/setMessage',
          setError: 'app/setError',
          setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
            save: 'application/save',
            find: 'application/find',
            drop: 'application/delete',
            getSource: 'source/find'
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
      getSource(id){
            var result = {}
            this.sources.map((el) => {
            if (id == el.id) {
                  result = el
                }
            })
            return result
      },
      toCreateKp(id){
        this.$router.push('/create/createKp/' + id)  
      },
      toShow(id){
         this.$router.push('../../clients/show/' + id)
      },
      toComments(id){
          this.$store.commit('comments/setValComment', {object_id:id,type:20, component:'application'}, {root: true})
      },
      showAppls(id){
         this.$router.push('/application/showAppls/'+ id)
      }
    },
    created() {
      this.setHeader('Заявки')
      this.page = this.pagination.page
      this.getSource()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
