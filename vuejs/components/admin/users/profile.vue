<template>
  <div class="profile">
    <div class="row">
      <div class="col-2">
        <p style="font-size:22px;text-align:start;line-height: 26px;font-family: MullerBold;color: #797373;">Настройки</p>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-3">
      </div>
      <div class="col-8" v-if="user">
        <div class="input-group col-6 mt-4">
          <div class="input-group-prepend">
            <span class="input-group-text" id="" style="min-width:99px">Логин:</span>
          </div>
          <input type="text" class="form-control" v-model="user.username" @change.prevent="updateUser">
        </div>
        <div class="input-group col-6 mt-4">
          <div class="input-group-prepend">
            <span class="input-group-text" id="" style="min-width:99px">Почта:</span>
          </div>
          <input type="text" class="form-control" disabled v-model="user.email" @change.prevent="updateUser">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Http from '../../../utils/http'

  export default {
    name: 'profile',
    data () {
      return {
        user: {},
      }
    },
    computed: {
      apiUrl () {
        return this.$store.state.app.apiUrl
      },
    },
    methods: {
      getUser() {
        Http.get(this.apiUrl + '/user?id=' + this.$route.params.id)
          .then((response) => {
            this.user = response.data[0]
          })
      },
      updateUser() {
        Http.post(this.apiUrl + '/user/update', this.user)
          .then((response) => {
            this.user = response.data
          })
      },
    },
    created () {
      this.$store.commit('app/setCurrentPageHeader', 'Профиль')
      this.getUser()
    }
  }
</script>
