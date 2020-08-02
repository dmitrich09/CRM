<template>
  <section>  
    <!--sortClients-->
      <div>
        <button class="btn btn-primary" @click="sortClients = !sortClients" v-show="!sortClients" >Сортировать компании</button>
      </div> 
       <div class="form-row"  v-if="sortClients" style="margin: 10px 0 10px 0;">
            <div class="col-2"  style="text-align: left;">
                <div style=" font-size: 70%;">ABC</div> 
                <select class="select"  v-model="search.abc_analize"  style=" width: 200px!important;">
                    <option value="0">Без категории</option>
                    <option  v-for="(data, id) in ABCList" :key="id"  v-bind:value="data.id" >{{data.name}}</option>
                </select> 
            </div>
            <div class="col-2"  style="text-align: left;">
                <div style=" font-size: 70%;">Тип контакта</div> 
                <select class="select"  v-model="search.clienttype_id"  style=" width: 200px!important;">
                    <option value="0">Без контакта</option>
                    <option  v-for="(data, id) in clientType" :key="id"  v-bind:value="data.id" >{{data.name}}</option>
                </select>
            </div>
            <div class="col-2"  style="text-align: left;">
                <div style=" font-size: 70%;">Сфера деятельности</div> 
                <select class="select"  v-model="search.sphere_id"  style=" width: 200px!important;">
                    <option value="0">Без категории</option>
                    <option  v-for="(data, id) in spheres" :key="id"  v-bind:value="data.id" >{{data.spherename}}</option>
                </select>
            </div>
            <div class="col-2"  style="text-align: left;">
                  <div style=" font-size: 70%;">Город</div> 
                  <select class="select"  v-model="search.city_id"  style=" width: 200px!important;">
                    <option value="0">Без категории</option>
                    <option  v-for="(data, id) in citys" :key="id"  v-bind:value="data.id" >{{data.name}}</option>
                  </select>
            </div>
            <div class="col-2"  style="text-align: left;">
               <div style=" font-size: 70%;">Менеджеры</div> 
                 <select class="select"  v-model="search.user_id"  style=" width: 200px!important;">
                    <option  v-for="(data, id) in users" :key="id"  v-bind:value="data.id" >{{data.username}}</option>
                </select>
            </div>
            <div class="col" style="margin: auto" >
                <button class="btn btn-success"  @click="findData()">Получить</button>   
            </div>
            <div class="col" style="margin: auto">
                <button class="btn btn-warning"  @click="form = {};sortClients=false ">Отменить</button> 
            </div>
    </div>
    <!--showXsl-->
      <div class="form-row" v-if="showXsl">
        <div class="col-1">
          <button class="btn btn-primary" @click="toClientslistform()">xsl</button>
        </div>
        <div class="col-3">
          <input class="form-control" @keyup="findData()" @change="findData()" v-model="search.var" type="text">
        </div>
        <div class="col-1">
          <button class="btn btn-primary" @click="showForm = !showForm;showXsl=false; ">Добавить</button>
        </div>
        <div class="col-3"></div>
            <pagination class="col-4" style="padding: 100% left" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <!--showForm-->
      <div class="form-row"  v-if="showForm" style="margin: 10px 0 10px 0;">
                    <div class="col-3">
                        <div style=" font-size: 70%;">Название</div>
                        <input type="text" class="form-control" v-model="form.name"  >
                    </div>
                    <div class="col-2">
                        <div style=" font-size: 70%;">Тип клинета</div>
                        <select class="select"  v-model="form.clienttype_id" >
                              <option value=""></option>
                              <option  v-for="type in clientType" :key="type.id" :value="type.id" >{{type.name}}</option>
                        </select>
                    </div>
                     <div class="col-2">
                        <div style=" font-size: 70%;">Город</div>
                          <select class="select"  v-model="form.city_id" >
                              <option  v-for="city in citys" :key="city.id" :value="city.id" >{{city.name}}</option>
                          </select>
                    </div>
                   <div class="col-3">
                        <div style=" font-size: 70%;">Сайт</div>
                        <input type="text" class="form-control" v-model="form.site" >
                    </div>
                    <div class="col-3">
                        <div style=" font-size: 70%;">Контактное лицо</div>
                        <input type="text" class="form-control" v-model="form.full_name" >
                    </div>
                    <div class="col-3">
                        <div style=" font-size: 70%;">Телефон</div>
                        <input type="text" class="form-control" v-model="form.phone" >
                    </div>
                    <div class="col" style="margin: auto" >
                            <button class="btn btn-success"  @click="saveData()">Сохранить</button>
                            <button class="btn btn-warning"  @click="search = {};showForm=false;showXsl=true ">Отменить</button>
                    </div>
                </div>
      <table class="table table-border table-hover">
        <tr  align="left">
          <th>Название</th>
           <th>Тип клиента</th>
            <th>Сфера деятельности</th>
             <th>Менеджер</th>
              <th>Город</th>
                <th>ИНН</th>
                 <th>Банк</th>
                  <th>БИК</th>
          <th></th>
          <th></th>
        </tr>
        <tbody v-for="model in datalist" :key="model.idx">
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']"  align="left">
            <td><a href="#" @click="toShow(model.id)">{{model.name}}</a></td>
             <td>{{getClientType(model.clienttype_id).name}}</td>
              <td>{{ getSpheres(model.sphere_id).spherename}}</td>
               <td>{{getManager(model.manager).username}}</td>
                <td>{{getCity(model.city_id).name}}</td>
                 <td>{{model.inn}}</td>
                  <td>{{model.bank}}</td>
                   <td>{{model.bik}}</td>
                   
            <td ><a  href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img src="../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px; margin-left:7px"></a></td>
             <td width="60px;" title="Удалить" ><a href="#">
              <vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
              </vue-confirmation-button></a>
            </td>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" v-model="edit.name" placeholder="Название"></td>
              <td>
                  <select class="select"  v-model="edit.clienttype_id" >
                      <option value="">Сфера деятельности</option>
                    <option  v-for="type in clientType" :key="type.id" :value="type.id" >{{type.name}}</option>
                </select>
              </td>
              <td>
                <select class="select"  v-model="edit.sphere_id" >
                      <option value="">Сфера деятельности</option>
                    <option  v-for="sphere in spheres" :key="sphere.id" :value="sphere.id" >{{sphere.spherename}}</option>
                </select>
              </td>
              <td>
                <select class="select"  v-model="edit.manager" >
                    <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                  </select>
              <td>
                  <select class="select"  v-model="edit.city_id" >
                      <option value="">Город</option>
                    <option  v-for="city in citys" :key="city.id" :value="city.id" >{{city.name}}</option>
                </select>
              </td>
              <td><input type="text" class="form-control" v-model="edit.inn" placeholder="ИНН"></td>
              <td><input type="text" class="form-control" v-model="edit.bank" placeholder="Банк"></td>
              <td><input type="text" class="form-control" v-model="edit.bik" placeholder="БИК"></td>
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

  export default {
    mixins: [Acl],
    name: 'citypek',
    data () {
      return {
        form:{
          name: '',
          unique_id: '',
          clienttype_id:'',
        },
        search: {
          var: null,
          sphere_id:'',
          clienttype_id:'',
          abc_analize:'',
          city_id:'',
          user_id:''
        },
        page: null,
        edit: null,
        showForm: false,
        showXsl: true,
        sortClients: false
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      pagination
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        spheres: 'sphere/datalist',
        citys: 'city/datalist',
        datalist: 'clients/datalist',
        pagination: 'clients/pagination',    
        users: 'app/users',
        clientType: 'dictionary/clientType',
        ABCList: 'dictionary/ABCList',

      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
          save: 'clients/save',
          find: 'clients/find',
          drop: 'clients/delete',
          findCity: 'city/findAll',
          findSphere: 'sphere/findAll',

      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
                .then(() =>{
                  this.findData()
                  this.showXsl=true
                  this.showForm=false
                  this.search = {}
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
        this.find({page:this.page,query: this.search})
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
      getClientType(id){
        var rezult = {}
        this.clientType.map((el) => {
          if(id == el.id){
            rezult = el
          }
        })
        return rezult
      },
      toShow(id){
         this.$router.push('../../clients/show/' + id)
      },
      toClientslistform(){
         this.$router.push('../../clients/addClientsListForm/')
      },
      getSpheres(id){
            var result = {}
            this.spheres.map((el) => {
                if (id == el.id) {
                    result = el
                }
            })
            return result
      },
    },
    created() {
      this.setHeader('Компании')
      this.page = this.pagination.page
      this.findCity()
      this.findSphere()
      this.findData()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
