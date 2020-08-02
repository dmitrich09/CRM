<template>
    <div id="restore">
        <div class="login_form">
            <div class="container h100 mt-5">
                <div class="row justify-content-md-center">
                    <div class="col-5">
                        <div class="auth-modal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="text-center">Восстановление пароля</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" @submit.prevent="restore">
                                            <div class="form-group">
                                                <input required v-model="email" class="form-control"
                                                       placeholder="Введите e-mail">
                                            </div>
                                            <a style="float:none" href="/login">Авторизация</a><br>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" @click="restore">Отправить</button>
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
  import Vuex from 'vuex'

  export default {
    data() {
      return {
        email: null,
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
        setError: 'app/setError',
      }),
      ...Vuex.mapActions({
        request: 'auth/requestpassword',
      }),
      restore() {
        this.setError(null)
        this.request({email: this.email})
          .then(() => {
            this.setMessage('Проверьте вашу почту')
          })
          .catch((err) => {
            this.setError(err.response.data.message)
          })
      }
    }
  }
</script>
