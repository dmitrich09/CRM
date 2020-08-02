<template>
  <section>
      <div class="form-row ">
        <div class="col-8 "></div>
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th>Номер договора</th> 
            <th>Выданный документ</th>
            <th>Название продукции</th>
            <th>Менеджер орк</th>
            <th>Выдан</th>
             <th>	Месяц/год оконания</th>
             <th>	Орган</th>
             <th>	№ сертификата</th>
             <th>Менеджер договора</th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
            <td></td>
            <td>{{model.id}}</td>
            <td>{{model.manager_id}}</td>
            <td>{{model.get_date}}</td>
            <td>{{model.license_to}}</td>
            <td>{{model.license_to}}</td>
            <td></td>
            <td></td>
            <td>{{model.a_man}}</td>

          
            <td width="60px;"><vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
            </vue-confirmation-button></td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
              <td><input type="text" class="form-control" v-model="edit.task_date" placeholder="Поставлена"></td>
              <td><input type="text" class="form-control" v-model="edit.description" placeholder="Задача"></td>
              <td><input type="text" class="form-control" v-model="edit.comment" placeholder="Комментарий"></td>
              <td><input type="text" class="form-control" v-model="edit.user_id" placeholder="Поставил"></td>
              <td><input type="text" class="form-control" v-model="edit.manager_id" placeholder="Менеджер"></td>
              <td><input type="text" class="form-control" v-model="edit.status_id" placeholder="Состояние "></td>
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
    name: 'citypek',
    data () {
      return {
        form:{
          task_date: '',
          manager_id: '',
          comment: 'Hoвая',
          status_id: 1,
          description: 'Hoвая'
        },
        search: null,
        page: null,
        edit: null,
        showForm: false
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      pagination,
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'document/datalist',
        pagination: 'document/pagination',
        users: 'app/users',
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
      getUser(id){
          var result = {}
            this.users.map((el) => {
            if (id == el.id) {
                  result = el
                }
            })
            return result
      },
      findData(){
        this.find({page:this.page, query: this.search})
      },
        toComments(id){
           this.$store.commit('comments/setValComment', {id:id}, {root: true})
        },
     },
    created() {
      this.setHeader('Задачи')
      this.page = this.pagination.page
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }

</style>
