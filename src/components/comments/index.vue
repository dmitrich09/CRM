 <template>
  <section>
        <div class="row">
           <div  class="col-12">
               <button type="button" class="close" @click="returnTo()" aria-hidden="true">×</button>
                <div  class="form-row ">
                    <label>Введите комментарий</label>
                    <textarea v-model="form.message" class="form-control" style="height: 150px;"  placeholder="Комментарий"></textarea>
                    <br>
                    <div style="margin-left: 90%">
                        <button class="btn btn-primary" style="width:120px;border-radius: 20px" @click="saveData()"> Дoбавить</button>
                    </div>
                </div>
                <div class="form-row justify-content-end"  style="margin-top:20px">
                  <div class="col-4"></div>
                  <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
                </div>
                <table class="table table-border table-hover" style="margin-top:20px">
                    <tr>
                        <th>Комментарий</th>  
                        <th>Поставлен</th>
                        <th>Менеджер</th>
                    </tr>
                    <tbody v-for="model in datalist" :key="model.id">
                      <tr  v-if="edit == null || edit.id != model.id"  :class="[model.deleted_at != null ? 'disabled' : '']">
                        <td>{{model.message}}</td>
                        <td>{{model.adddate}}</td>
                        <td>{{getManager(model.user_id).username}}</td>
                        <td ><a v-if="model.deleted_at == null || model.deleted_at == ''" class="fui-new" @click="toChange(model)" ></a></td>
                        <td width="60px;">
                            <vue-confirmation-button
                                v-if="model.deleted_at == null || model.deleted_at == ''"
                                :css="'fui-trash'"
                                v-on:confirmation-success="deleteData(model.id)">
                            </vue-confirmation-button>
                        </td>
                      </tr>
                      <tr v-if="edit != null && edit.id == model.id">
                          <td><input type="text" class="form-control" v-model="edit.message" placeholder="Комментарий"></td>
                          <td><input type="text" class="form-control" v-model="edit.adddate" placeholder="Название"></td>  
                          <td>
                            <select class="select"  v-model="edit.manager_id" >
                                <option  v-for="user in users" :key="user.id" :value="user.id" >{{user.username}}</option>
                              </select>
                          </td> 
                      <td><a class="fui-check" @click="update"></a></td>
                        <td><a class="fui-cross" @click="edit = null"></a></td>   
                      </tr>
                    </tbody>
                </table>
            </div>
          </div>
          <modal v-if="showModal"></modal>
     </section>
</template>

<script>
  import Acl from '../../utils/acl'
  import Vuex from 'vuex'
  import vueConfirmationButton from '../../utils/confirmButton'
  import pagination from "../../utils/pagination"
  

  export default {
    mixins: [Acl],
    name: 'comments',
    data () {
      return {
        form:{
          object_id: '',
          type: '',
          message: ''
        },
        search: null,
        page: null,
        edit: null,
        showModal:false
      }
    },
    components: {
      VueConfirmationButton: vueConfirmationButton,
      pagination,
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'comments/datalist',
        pagination: 'comments/pagination',
        valComment: 'comments/valComent',
        storeForm: 'mybuisness/form',
        users: 'app/users'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader',
        setValComment:'comment/setValComment' 
      }),
      ...Vuex.mapActions({
        save: 'comments/addComment',
        updateComment: 'comments/updateComment',
        find: 'comments/find',
        drop: 'comments/delete',
      }),
      saveData(){
        this.setError(null)
        this.save(this.form)
        this.find(this.valComment)
        this.updateCommentComponent()
      },
      update() {
        this.setError(null)
        this.updateComment(this.edit)
                .then(() =>{
                  this.edit = null
                  this.find(this.valComment)
                })
      },
      findData(){
        this.find(this.valComment)
      },
      setPage(i){
        this.page = i
        this.valComment.page = this.page   
        this.findData(this.valComment)
      },
       toChange(model){
        this.edit = Object.assign({}, model)
      },
      returnTo(){
        window.location.href = '/mybuisness?active='+ this.valComment.component
       // this.$router.push('../../mybuisness?active='+ this.valComment.component)
      },
      createForm(){
         this.form = Object.assign({},this.valComment)
      },
      updateCommentComponent(){
        var comp = this.valComment.component
        this.$store.dispatch( comp +'/find' , this.storeForm )
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
      setCommentStoreNull(){
          this.valComent.object_id = ''
          this.valComent.type = ''
          this.valComent.component = ''
          this.setValComment(this.valComent)
      }
    },
    created() {
      this.setHeader('Комментарии для  ' + this.valComment.component)
      this.findData()
      this.createForm()
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }
</style>
