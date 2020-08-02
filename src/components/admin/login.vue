<template>
  <div class="login_form">
    <div class="container h100 mt-5 ">
      <div class="row justify-content-md-center">
        <div class="col-5">
          <div class="auth-modal">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="text-center" >Авторизация</h5>
                </div>
                <div class="modal-body">
                  <form action="" @submit.prevent="auth">
                    <div class="form-group">
                      <input required v-model="login" class="form-control" placeholder="Введите логин" >
                    </div>
                    <div class="form-group">
                      <input required v-model="password" class="form-control"  type="password" placeholder="Введите пароль">
                    </div>
                    <a id="recovery" style="float:none" href="/restore">Забыли пароль?</a><br>
                  </form>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-primary" @click="auth">Войти в систему</button>
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
    name: 'login',
    data () {
      return {
        login: '',
        password: '',
      }
    },
    methods: {
      ...Vuex.mapActions({
        logIn: 'auth/auth',
      }),
      auth() {
        const { login, password } = this
        this.logIn({login, password}).then(() => {
          this.$router.push('/')
        })
      }
    }
  }
</script>
