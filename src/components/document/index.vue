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
        <tr  align="left">
            <th>Название</th> 
            <th>Полное название</th>
            <th>Для документов</th>
            <th>Протокол в документах</th>
            <th>Инспекционный контроль</th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
            <td>{{model.name}}</td>
            <td>{{model.fullname}}</td>
            <td>{{model.for_doc}}</td>
            <td>{{model.is_include}}</td>
            <td>{{model.is_control}}</td>
            <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" ></a></td>   
            <td width="60px;"><vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
            </vue-confirmation-button></td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" v-model="edit.name" placeholder="Название"></td>
              <td><input type="text" class="form-control" v-model="edit.fullname" placeholder="Полное название"></td>
              <td><input type="text" class="form-control" v-model="edit.for_doc" placeholder="Для документов"></td>
              <td><input type="text" class="form-control" v-model="edit.is_include" placeholder="Протокол в документах"></td>
              <td><input type="text" class="form-control" v-model="edit.is_control" placeholder="Инспекционный контроль"></td>
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
          fullname: 'Hoвая',
          for_doc: 'Hoвая',
          is_include: 1,
          is_control: 2
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
        datalist: 'document/datalist',
        pagination: 'document/pagination',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'document/save',
        find: 'document/find',
        drop: 'document/delete'
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
      this.setHeader('Документ')
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
