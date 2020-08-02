<template>
    <section>
        <div class="row">
            <h4 class="col-1">{{name}}</h4>
        </div>
        <div class="row">
          <div class="col-12" >
            <table class="table clients table-hover">
              <thead>
                <tr>
                  <th scope="col">Название</th>
                  <th scope="col">Описание</th>
                    <th></th>
                </tr>
              </thead>
               <tbody v-for="(r, idx) in rights" :key="idx">
                   <tr>
                    <td>{{ r.name }}</td>
                    <td>{{ r.description }}</td>
                    <td>
                        <input v-if="r.active == true" type="checkbox" v-model="r.active" @change="revoke(r.name)">
                        <input v-if="r.active != true" type="checkbox" v-model="r.active" @change="assign(r.name)">
                    </td>
                   </tr>
                </tbody>
            </table>
          </div>
       </div>
    </section>
</template>
<script>
    import Http from '../../../utils/http'
    import Acl from '../../../utils/acl'
    import Vuex from 'vuex'

    export default {
        mixins: [Acl],
        data () {
            return {
                permissions: [],
                userPermissions: [],
                user: null
            }
        },
        computed: {
          ...Vuex.mapGetters({
            apiUrl:'app/apiUrl',
          }),
          name(){
            if (this.user != null){
                return this.user.username
            }
            return ''
          },
          rights () {
            var arr = []
            this.permissions.map((el) => {
              var right = el
              right.active = false
              this.userPermissions.map((usr) => {
                if (usr.name == right.name) {
                  right.active = true
                }
              })
              arr.push(right)
            })
            return arr
          }
        },
        methods: {
              ...Vuex.mapMutations({
                setMessage: 'app/setMessage',
                setError: 'app/setError'
              }),
            assign(permission) {
                var user_id = this.$route.params.id
                Http.post(this.apiUrl + 'role/assign', {"name": permission, "user_id": user_id})
                    .then(()=>{
                      this.getPermissions()
                      this.getUserPermissions()
                    })
            },
            revoke(permission) {
                var user_id = this.$route.params.id
                if (!confirm('Вы действительно хотите снять роль ' + permission + ' с этого пользователя?')) {
                    return
                }
                Http.post(this.apiUrl + 'role/revoke', {"name": permission, "user_id": user_id})
                    .then(() => {
                      this.getPermissions()
                      this.getUserPermissions()
                    })
            },
            getUser() {
                var user_id = this.$route.params.id
                Http.get(this.apiUrl + 'user?id=' + user_id)
                 .then((response) => {
                   this.user = response.data[0]
                 })
            },
            getPermissions() {
                Http.get(this.apiUrl + 'role/permissions')
                    .then((response) => {
                      this.permissions = response.data
                    })
            },
            getUserPermissions() {
                var user_id = this.$route.params.id
                Http.get(this.apiUrl + 'role/permissions?user_id=' + user_id)
                  .then((response) => {
                    this.userPermissions = response.data
                  })
              },
        },
        created () {
            this.$store.commit('app/setCurrentPageHeader', 'Права')
            this.getUser()
            this.getPermissions()
            this.getUserPermissions()
        }
    }
</script>
