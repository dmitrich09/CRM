<template>
    <section>
        <div class="form-row justify-content-end">
           <div class="col-4"></div>
           <pagination class="col-4" @setPage="setPage" :padding="2" :totalpages="pagination.total/pagination.limit" :page="pagination.page"></pagination>
        </div>
       <table class="table table-border table-hover" style="margin-top:20px">
            <tr align="left">
                <th>Название</th>
                <th>Имя</th>
                <th></th>
                <th></th>
            </tr>
            <tbody v-for="model in datalist.data" :key="model.idx">
            <tr align="left">
                <td>{{model.name}}</td>
                <td>{{model.contact_name}}</td>
            </tr>
            </tbody>
        </table>
    </section>
</template>

<script>
  import Acl from '../../../utils/acl'
  import Vuex from 'vuex'
  import pagination from '../../../utils/pagination'
 
  export default {
    mixins: [Acl],
    name: 'staff',
    props:['search'],
    data() {
      return {
        form: {
        },
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
        datalist: 'search/byContName',
        pagination: 'search/pagination'
    }),
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
      setPage(i) {
        this.page = i
        this.findData()
      },
      findData() {
        this.find({page: this.page, search: this.search})
      },
    },
    created() {
      this.setHeader('Сотрудники')
    }
  }
</script>
<style>
    .disabled {
        color: lightgrey;
    }

</style>
