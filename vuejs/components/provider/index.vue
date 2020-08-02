<template>
  <section>
      <div class="form-row">
        <div class="col-3">
          <input class="form-control" @keyup="findData()" @change="findData()" v-model="search" type="text">
        </div>
        <div class="col-1">
          <button class="btn btn-primary" @click="saveData()">Добавить</button>
        </div>
        <div class="col-4"></div>
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover">
        <tr align="left">
            <th>Название</th> 
            <th>E-mail</th>
            <th>Юр.адрес</th>
            <th>Почт.адрес</th>
            <th>Адрес</th>
            <th>Контакт</th>
            <th>Телефон</th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']"  align="left">
            <td>{{model.name}}</td>
            <td>{{model.email}}</td>
            <td>{{model.lawadress}}</td>
            <td>{{model.postadress}}</td>
            <td>{{model.adress}}</td>
            <td>{{model.contact}}</td>
            <td>{{model.phone}}</td>
            <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" ></a></td>   
            <td width="60px;"><vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
            </vue-confirmation-button></td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
            <td><input type="text" class="form-control" v-model="edit.name" placeholder="Название"></td>
               <td><input type="text" class="form-control" v-model="edit.email" placeholder="E-mail"></td>
              <td><input type="text" class="form-control" v-model="edit.lawadress" placeholder="Юр.адрес"></td>
              <td><input type="text" class="form-control" v-model="edit.postadress" placeholder="Почт.адрес"></td>
              <td><input type="text" class="form-control" v-model="edit.adress" placeholder="Адрес"></td>
               <td><input type="text" class="form-control" v-model="edit.contact" placeholder="Контакт"></td>
                  <td><input type="text" class="form-control" v-model="edit.phone" placeholder="Телефон"></td>
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
  import pagination from "../../utils/pagination";

  export default {
    mixins: [Acl],
    name: 'citypek',
    data () {
      return {
        form:{
          name: 'Hoвая',
          email: 'Hoвая',
          lawadress: 'Hoвая',
          postadress: 'Hoвая',
          adress: 'Hoвая',
          contact: 'Hoвая',
          phone: 'Hoвая',
        },
        search: null,
        page: null,
        edit: null,
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      pagination
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'provider/datalist',
        pagination: 'provider/pagination',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'provider/save',
        find: 'provider/find',
        drop: 'provider/delete'
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
        this.find({page:this.page, query: this.search})
      }
    },
    created() {
      this.setHeader('Поставщик')
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
