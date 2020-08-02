<template>
  <section>
      <div class="form-row">
        <div class="col-3">
          <input class="form-control" @keyup="findData()" @change="findData()" v-model="search" type="text">
        </div>
        <div class="col-1">
          <button class="btn btn-primary" @click="saveData">Добавить</button>
        </div>
        <div class="col-4"></div>
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover">
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th></th>
          <th></th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id">
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']">
            <td>{{model.id}}</td>
             <td>{{model.spherename}}</td>
            <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" ></a></td>
            <td width="60px;"><vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
            </vue-confirmation-button></td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" v-model="edit.id" placeholder="ID"></td>
              <td><input type="text" class="form-control" v-model="edit.spherename" placeholder="Название"></td>  
           <td><a class="fui-check" @click="update()"></a></td>
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
        datalist: 'sphere/datalist',
        pagination: 'sphere/pagination',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        save: 'sphere/save',
        find: 'sphere/find',
        drop: 'sphere/delete'
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
      this.setHeader('Сферы деятельности')
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
