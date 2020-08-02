<template>
    <section>
        <div class="form-row justify-content-end">
           <div class="col-4"></div>
           <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
        </div>
        <table class="table table-border table-hover" style="margin-top:20px">
            <tr align="left">
                <th>Название</th>
                <th>Тип клиента</th>
                <th>Сфера деятельности</th>
                <th>Менеджер</th>
                <th>Область</th>
                <th>Инн</th>
                <th>Банк</th>
                <th>БИК</th>
                <th></th>
            </tr>
            <tbody v-for="model in datalistByInn" :key="model.idx">
            <tr v-if="edit == null || edit.id != model.id" :class="[model.deleted_at != null ? 'disabled' : '']"
                align="left">
                <td>{{model.name}}</td>
                <td>{{getClientType(model.clienttype_id).name}}</td>
                <td>{{getSpheres(model.sphere_id).spherename}}</td>
                <td>{{getManager(model.manager).username}}</td> 
                <td>{{getCity(model.city_id).name}}</td>
                <td>{{model.inn}}</td>
                <td>{{model.bank}}</td>
                <td>{{model.bik}}</td>
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
    name: 'inn',
    props:['search'],
    data() {
      return {
        form: {},
        page: null,
        edit: null,
      }
    },
    components: {
      pagination
    },
    computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl',
          datalist: 'search/byInn',
          clientType: 'dictionary/clientType',
          citys: 'city/datalist',
          spheres: 'sphere/datalist',
          users: 'app/users',
          pagination: 'search/pagination', 
    }),
     datalistByInn(){
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
            find: 'search/findCients',
      }),
      setPage(i){
        this.page = i
        this.findData(this.search)
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
    },
    created() {
      this.setHeader('Инн')
      this.page = this.pagination.page
    }
  }
</script>
<style>
    .disabled {
        color: lightgrey;
    }

</style>
