<template>
  <div id="app">
    <UserManagement />
  </div>
</template>

<script>
import UserManagement from './components/UserManagement.vue'

export default {
  name: 'App',
  components: {
    UserManagement
  }
}
</script>

<style>
@import 'bootstrap/dist/css/bootstrap.min.css';
</style>
