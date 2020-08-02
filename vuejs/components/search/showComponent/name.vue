<template>
    <section>
        <div class="row">
            <div class="col-1">
                <button class="btn btn-primary" @click="showForm = !showForm ">Добавить</button>
            </div>
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
        </div>
        <div class="form-row justify-content-end">
           <div class="col-4"></div>
           <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
        </div>
        <table class="table table-border table-hover" style="margin-top:20px">
            <tr align="left">
                <th></th>
                <th>Тип клиента</th>
                <th>Сфера деятельности</th>
                <th>Менеджер</th>
                <th>Область</th>
                <th>Инн</th>
                <th>Банк</th>
                <th>БИК</th>
                <th></th>
            </tr>
            <tbody v-for="model in datalistByName" :key="model.idx">
            <tr v-if="edit == null || edit.id != model.id" :class="[model.deleted_at != null ? 'disabled' : '']"
                align="left">
                <td><a href="#" @click="toShow(model.id)">{{model.name}}</a></td>
                <td>{{getClientType(model.clienttype_id).name}}</td>
                <td>{{getSpheres(model.sphere_id).spherename}}</td>
                <td>{{getManager(model.manager).username}}</td> 
                <td>{{getCity(model.city_id).name}}</td>
                <td>{{model.inn}}</td>
                <td>{{model.bank}}</td>
                <td>{{model.bik}}</td>
                <td><a href="#" v-if="model.deleted_at == null || model.deleted_at == ''" @click="toChange(model)"><img
                        src="../../../../static/images/iconsColors/edit.png" title="Редактировать" style="max-width:20px"></a>
                </td>
                <td width="60px;">
                    <a href="#">
                    <vue-confirmation-button
                            v-if="model.deleted_at == null || model.deleted_at == ''"
                            :css="'fui-trash'"
                            v-on:confirmation-success="deleteData(model.id)"  title="Удалить">
                    </vue-confirmation-button >
                    </a>
                </td>
            </tr>    
            <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" v-model="edit.name" placeholder="Название" style="width: 100px"></td>
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
              <td><input type="text" class="form-control" v-model="edit.inn" placeholder="ИНН" style="width: 100px"></td>
              <td><input type="text" class="form-control" v-model="edit.bank" placeholder="Банк" style="width: 100px"></td>
              <td><input type="text" class="form-control" v-model="edit.bik" placeholder="БИК" style="width: 100px"></td>
              <td><a class="fui-check" @click="update"></a></td>
              <td><a class="fui-cross" @click="edit = null"></a></td>
            </tr>
            </tbody>
        </table>
    </section>
</template>

<script>
  import Acl from '../../../utils/acl'
  import Vuex from 'vuex'
  import vueConfirmationButton from '../../../utils/confirmButton'
  import pagination from "../../../utils/pagination"

  export default {
    mixins: [Acl],
    name: 'name',
    props:['search'],
    data() {
      return {
        form: {
        },
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      pagination
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        users: 'app/users',
        datalist: 'search/byName',
        clientType: 'dictionary/clientType',
        citys: 'city/datalist',
        spheres: 'sphere/datalist',
        pagination: 'search/pagination', 
    }),
    datalistByName(){
        var obj =  Object.assign({}, this.datalist)
        return obj.data
    },

    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader: 'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'clients/save',
        find: 'search/findCients',
        drop: 'clients/delete'
      }),
      setPage(i){
        this.page = i
        this.findData(this.search)
      },
      saveData() {
        this.setError(null)
        this.save(this.form)
          .then(() => {
            this.find(this.storeForm)
          })
      },
      update() {
        this.setError(null)
        this.save(this.edit)
          .then(() => {
            this.edit = null
            this.find({search:this.search}) 
          })
      },
      deleteData(id) {
        this.setError(null)
        this.drop(id)
          .then(() => {
            this.find(this.storeForm)
          })
      },
      toChange(model) {
        this.edit = Object.assign({}, model)
      },
      findData() {
          this.find({page: this.page, search: this.search})
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
       getSpheres(id){
            var result = {}
            this.spheres.map((el) => {
                if (id == el.id) {
                    result = el
                }
            })
            return result
      },
      toShow(id){
         this.$router.push('../../clients/show/' + id)
      },
    },
    created() {
      this.setHeader('Название')
       this.page = this.pagination.page
    }
  }
</script>
<style>
    .disabled {
        color: lightgrey;
    }

</style>
