<template>
    <div id="activate mt-4">
        <div class="login_form mt-2">
            <div class="container h100 mt-5">
                <div class="row justify-content-md-center">
                    <div class="col-5">
                        <div class="auth-modal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="text-center">Активация пароля</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div v-if="message" class="alert alert-danger">
                                            {{ message }}
                                        </div>
                                        <form action="" @submit.prevent="activate">
                                            <div class="form-group">
                                                <input required v-model="password" class="form-control" type="password"
                                                       placeholder="Пароль">
                                            </div>
                                            <div class="form-group">
                                                <input required v-model="confirm" class="form-control" type="password"
                                                       placeholder="Подтверждение">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" @click="activate">Отправить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  import Http from '../../utils/http'
  import Vuex from 'vuex'
  import acl from "../../utils/acl";

  export default {
    mixins: [acl],
    data() {
      return {
        key: null,
        password: null,
        confirm: null,
      }
    },
    computed: {
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl'
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError'
      }),
      check() {
        this.key = this.$route.params.key
      },
      activate() {
        this.$store.commit('app/setError')
        Http.post(this.apiUrl + '/auth/activate', {key: this.key, password: this.password, confirm: this.confirm})
          .then(() => {
            this.$router.push('/login')
          })
          .catch((err)=>{
            this.setError(err.response.data.message)
          })
      }
    },
    created() {
      this.check()
    }
  }
</script>
