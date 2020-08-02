<template>
  <section>
      <div class="form-row ">
        <div class="col-8 "></div>
        <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
      </div>
      <table class="table table-border table-hover" style="margin-top:20px">
        <tr  align="left">
            <th>Наш id</th> 
            <th>id zadarma</th>
            <th>Дата</th>
            <th>Откуда</th>
            <th>Куда</th>
            <th>Длительность</th>
            <th>Тип</th>
            <th>Оператор</th>
            <th>Прослушать</th>
        </tr>
        <tbody v-for="model in datalist" :key="model.id"> 
          <tr    :class="[model.deleted_at != null ? 'disabled' : '']" align="left">
            <td>{{model.id}}</td>
            <td>{{model.zad_call_id}}</td>
            <td>{{model.callstart}}</td>
            <td>{{model.sip}}</td>
            <td>{{model.destination}}</td>
            <td>{{model.seconds}}</td>
            <td>{{model.incoming==1  ? 'Входящий' : 'Исходящий'}}</td>
            <td>{{getManager(model.user_id).username ?getManager(model.user_id).username : 'Не задано' }}</td>
            <td><audio src="" preload="auto" controls></audio></td>
          </tr>  
        </tbody>
      
      </table>
  </section>
</template>

<script>
  import Acl from '../../../utils/acl'
  import Vuex from 'vuex'
  import pagination from "../../../utils/pagination"

  export default {
    mixins: [Acl],
    name: 'citypek',
    data () {
      return {
        page: null,
      }
    },
    components: {
      pagination,
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        datalist: 'calls/datalist',
        pagination: 'calls/pagination',
        users: 'app/users',
        storeForm: 'mybuisness/form',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      setPage(i){
        this.page = i
        this.findData(this.storeForm)
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
      getManager(id){
          var result = {}
            this.users.map((el) => {
            if (id == el.id) {
                  result = el
                }
            })
            return result
      }
     },
    created() {
      this.setHeader('История звонков')
      this.page = this.pagination.page
    }
  }
</script>
<style>
  .disabled {
    color:lightgrey;
  }

</style>
