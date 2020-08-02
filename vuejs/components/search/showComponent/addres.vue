<template>
    <section>
       <div class="form-row justify-content-end">
           <div class="col-4"></div>
           <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
        </div>
       <table class="table table-border table-hover" style="margin-top:20px">
            <tr align="left">
                <th>Название</th>
                <th>ИНН</th>
                <th>Адрес</th>
                <th></th>
            </tr>
            <tbody v-for="model in datalistByAddress" :key="model.idx">
            <tr align="left">
                <td>{{model.name}}</td>
                <td>{{model.inn}}</td>
                <td>{{model.factaddress}}</td>
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
    name: 'addres',
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
        pagination
    },
    computed: {
      ...Vuex.mapGetters({
          apiUrl: 'app/apiUrl',
          datalist: 'search/byAddress',
          pagination: 'search/pagination', 
      }),
      datalistByAddress(){
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
          find: 'search/findCients'
      }),
      setPage(i) {
        this.page = i
        this.find()
      },
      findData() {
        this.find({page: this.page, search: this.search})
      },
    },
    created() {
      this.setHeader('Сотрудники')
      this.page = this.pagination.page
    }
  }
</script>
<style>
    .disabled {
        color: lightgrey;
    }

</style>
