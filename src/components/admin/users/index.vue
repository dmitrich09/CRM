<template>
    <div>
        <div class="row">
            <div class="col-3">
                <input class="form-control" @keyup="findUsers(search)" @change="getUsers(search)" v-model="search" type="text">
            </div>
            <div class="col-1">
                <button v-if="can('rbacManage')" class="btn btn-primary" @click.prevent="addModalShow = true">Добавить
                </button>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <table class="table clients table-hover">
                <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col">Id</th>
                    <th scope="col">Фамилия</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Электронный адрес</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Права / Роль</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, idx) in users" v-bind:key="user.id">
                    <td>{{ idx + 1 }}</td>
                    <td><a :href="'/profile/' + user.id">{{ user.id }}</a></td>
                    <td></td>
                    <td>{{ user.username }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ userStatuses[user.status]}}</td>
                    <td><a v-if="can('rbacManage')" :href="'/rightsUser/' + user.id"><i class="fas fa-tools"></i></a>
                    </td>
                    <td>
                        <confirm-button v-if="can('rbacManage')"
                                        :css="'fui-trash'"
                                        v-on:confirmation-success="deleteUsers(user.id)">
                        </confirm-button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <add-form
                @closeAddForm="closeAddForm"
                @addUser="addUser"
                :isVisible="addModalShow"
                :newUser="newUser"/>
    </div>
</template>

<script>
  import Http from '../../../utils/http'
  import AddForm from './addForm'
  import Acl from '../../../utils/acl'
  import Vuex from 'vuex'
  import confirmButton from "../../../utils/confirmButton";

  export default {
    mixins: [Acl],
    name: 'users',
    data() {
      return {
        addModalShow: false,
        search: '',
        newUser: {
          email: null,
        },
      }
    },
    components: {
      AddForm, confirmButton

    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
        userStatuses: 'app/userStatuses',
        users: 'app/users'
      }),
    },
    methods: {
      ...Vuex.mapActions({
        getUsers: 'app/getUsers',
        deleteUser: 'app/deleteUser'
      }),
      ...Vuex.mapMutations({
        setError: 'app/setError',
      }),
      findUsers(query){
        this.getUsers(query)
      },
      deleteUsers(id) {
        this.deleteUser(id)
          .then(() => {
            this.getUsers()
          })
      },
      addUser() {
        Http.post(this.apiUrl + 'user/create', {"User": {"email": this.newUser.email}})
          .then(() => {
            this.getUsers()
            this.newUser.email = null
            this.closeAddForm()
          })
          .catch((e) => {
            this.setError(e.response)
          })

      },
      closeAddForm() {
        this.addModalShow = false
      },
    },
    created() {
      this.$store.commit('app/setCurrentPageHeader', 'Список пользователей')
      this.getUsers()
    }
  }
</script>
