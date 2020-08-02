<template>
  <div class="col-6 offset-3">
    <div class="input-group col-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="min-width:160px">Новый пароль:</span>
        </div>
        <input type="password" class="form-control" v-model="password" autocomplete="off">
    </div>
    <div class="input-group col-12" style="margin-top:10px;">
        <div class="input-group-prepend">
            <span class="input-group-text" id="" style="min-width:160px">Подтверждение:</span>
        </div>
        <input type="password" class="form-control" v-model="confirm" autocomplete="off">
    </div>
    <div class="col-12" style="margin-top:10px;">
        <button class="btn btn-primary" style="float:left" @click="changePassword">Сменить</button>
    </div>
  </div>
</template>

<script>
  import acl from "../../../utils/acl"
  import Vuex from 'vuex'
  export default {
    name: 'chPass',
    mixins: [acl],
    data () {
      return {
        password: null,
        confirm: null,
      }
    },
    computed: {
      apiUrl () {
        return this.$store.state.app.apiUrl
      },
      ...Vuex.mapGetters({
        apiUrl: 'app/apiUrl',
      }),
    },
    methods: {
      ...Vuex.mapMutations({
        setMessage: 'app/setMessage',
        setError: 'app/setError',
        setHeader:'app/setCurrentPageHeader'
      }),
      ...Vuex.mapActions({
        change: 'auth/changePassword',
      }),
      changePassword() {
        this.change({"password": this.password, "confirm": this.confirm})
          .then(() => {
            this.password = null
            this.confirm = null
          })
      },
    },
    created() {
      this.setHeader('Смена пароля')
    }
  }
</script>
