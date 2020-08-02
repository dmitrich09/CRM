<template>
    <div class="pagination" v-if="getTotal > 1">
        {{page}} из {{getTotal}}
        <ul>
            <li v-for="i in visiblelist" :key="i" :class="i == page ? 'active' : '' "><a href="#" @click="set(i)">{{i}}</a></li>
        </ul>
    </div>

</template>

<script>
  export default {
    name: 'pagination',
    props: ['padding', 'page', 'totalpages'],
    data() {
      return {}
    },
    computed: {
      currentPage() {
        return this.page != null ? +this.page : 1
      },
      getPadding() {
        return this.padding != null ? +this.padding : 0
      },
      getTotal() {
        return this.totalpages != null ? Math.ceil(+this.totalpages) : 0
      },
      min() {
        var mn = this.currentPage - this.getPadding
        if (mn > 0) {
          return mn
        }
        return 1
      },
      max() {
        var mx = this.min + 2 * this.getPadding
        return mx > this.getTotal ? this.getTotal : mx
      },
      visiblelist() {
        var arr = []
        for (var i = this.min; i <= this.max; i++){
          arr.push(i)
        }
        return arr
      }
    },
    methods: {
      set(i){
        this.$emit('setPage', i)
      }
    },
  }
</script>
<style scoped>
    .pagination {
        margin-top: 0px;
    }
    .pagination li.active > a,.pagination li > a:hover, .pagination li.active > span {
        background-color: #D03D72;
        color: white;
        border-color: #D03D72;
    }
</style>

