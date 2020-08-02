<template>
  <section>
      <div class="form-row"  style="margin: 10px 0 10px 0;">
          <div style=" font-size: 17px;">Комментарии</div> 
              <textarea type="text" class="form-control com" v-model="form.message" ></textarea>
              <div class="col-2" style="margin-top:30px">
                  <button class="btn btn-primary" @click="saveData()">Добавить Комментарии</button>
              </div>  
          </div>
      <div class="form-row">
          <div class="col-4"></div>
          <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover">
          <tr>
              <th>Комментарии</th>
              <th>Поставил</th>
              <th>Менеджер</th>
              <th></th>
              <th></th>
          </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']">
              <td>{{model.message}}</td>
              <td>{{model.adddate}}</td>
              <td>{{getManager(model.user_id).username}}</td>
            <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" ></a></td>
            <td width="60px;"><vue-confirmation-button
                    v-if="model.deleted_at == null || model.deleted_at == ''"
                    :css="'fui-trash'"
                    v-on:confirmation-success="deleteData(model.id)">
            </vue-confirmation-button></td>
          </tr>
          <tr v-if="edit != null && edit.id == model.id">
               <td><input type="text" class="form-control" v-model="edit.message" placeholder="ID"></td>
               <td><input type="text" class="form-control" v-model="edit.adddate" placeholder="Название"></td>
                 <td>
                    <select class="select"  v-model="edit.user_id" >
                        <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                      </select>
                  </td> 
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
    props:['object_id'],
    data () {
      return {
        form:{
          object_id: this.object_id,
          type: 20,
          message: 'Add comment!!!'
        },
        search: null,
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
        datalist: 'comments/datalist',
        pagination: 'comments/pagination',
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
        save: 'comments/addComment',
        find: 'comments/find',
        drop: 'comments/delete'
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
        this.storeForm.page = this.page 
        this.find(this.storeForm )
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
        this.find({type:this.form.type, object_id:this.form.object_id})
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
     },
    created() {
      this.setHeader('Заявки - комментарии')
      this.findData()
      this.page = this.pagination.page
    }
  }
</script>
<style>
.form-control.com{
    height:150px!important;
  }

</style>
