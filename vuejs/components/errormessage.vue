<template>
    <section>
        <div class="alert alert-danger" v-if="error != null">
            {{error}}
            <button type="button" class="close" @click="errorNull">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success"  v-if="message != null">
            {{message}}
            <button type="button" class="close" @click="messageNull">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </section>
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
        apiUrl: 'app/apiUrl',
        message: 'app/message',
        error: 'app/error',
      }),
    },
    methods: {
        messageNull(){
            this.$store.commit('app/setMessage', null)
        },
        errorNull(){
          this.$store.commit('app/setError', null)
        }
    }
}
</script>
