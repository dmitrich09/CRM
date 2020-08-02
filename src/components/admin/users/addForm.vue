<template>
  <section v-if="isVisible">
    <div class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="input_title">Введите email</div>
            <input class="form-control" name="" v-model="newUser.email">
          </div>
          <div class="modal-footer">
            <a href="" id="cancel"  @click.prevent="close" class="btn btn-secondary">Отменить</a>
            <a href="" id="save" class="btn btn-primary" @click.prevent="addUser">Добавить</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
  export default {
    props: ['isVisible', 'newUser'],
    methods: {
      addUser () {
        this.$emit('addUser')
      },
      close () {
        this.$emit('closeAddForm')
      },
    }
  }
</script>
<style>
  .modal{
    display: block;
  }
</style>
