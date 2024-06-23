<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <div class="form-container">
          <h2>User Form</h2>
          <form @submit.prevent="onSubmit">
            <div class="mb-3">
              <label for="name" class="form-label">Name:</label>
              <input type="text" v-model="formName" class="form-control" id="name" >
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="email" v-model="formEmail" class="form-control" id="email" >
            </div>
            <button type="submit" class="btn btn-success me-2">Save</button>
            <button type="button" @click="updateUserDetails" class="btn btn-primary">Update</button>
          </form>
        </div>
      </div>
      <div class="col-md-6">
        <div class="user-list-container">
          <div class="user-list-header text-center">
            <h2>List of Users</h2>
            <hr />
          </div>
          <ul class="list-group">
            <li v-for="user in users" :key="user.id" class="list-group-item user-item">
              <div class="user-info">
                <h5>{{ user.name }}</h5>
                <p>{{ user.email }}</p>
              </div>
              <div class="user-actions">
                <button @click="chooseUser(user)" class="btn btn-primary mb-2">Choose</button>
                <button @click="deleteUser(user.id)" class="btn btn-danger mt-2">Remove</button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data() {
    return {
      formName: '',
      formEmail: '',
      isEditMode: false,
      editingUserId: null,
    }
  },
  computed: {
    ...mapGetters(['users', 'currentUser'])
  },
  methods: {
    ...mapActions(['fetchUsers', 'addUser', 'updateUser', 'deleteUser', 'setCurrentUser']),
onSubmit() {
  // Check if either the name or email is empty
  if (!this.formName.trim() || !this.formEmail.trim()) {
    // Display a popup alert if either is empty
    alert('Both name and email must be provided.');
    return; // Exit the function to prevent further execution
  }

  // Proceed with adding the user if both fields are filled
  this.addUser({
    name: this.formName,
    email: this.formEmail
  }).then(() => {
    this.formName = '';
    this.formEmail = '';
    // Assuming you have a method or action to clear the current user selection
    this.setCurrentUser({ id: null, name: '', email: '' });
    this.isEditMode = false; // Reset edit mode if applicable
  });
},
updateUserDetails() {
  // Check if either the name or email is empty
  if (!this.formName.trim() || !this.formEmail.trim()) {
    // Display a popup alert if either is empty
    alert('Both name and email must be provided.');
    return; // Exit the function to prevent further execution
  }

  // Proceed with the update if both fields are filled
  if (this.isEditMode) {
    this.updateUser({ id: this.editingUserId, name: this.formName, email: this.formEmail }).then(() => {
      this.resetForm();
    });
  }
},
    chooseUser(user) {
      this.editingUserId = user.id;
      this.formName = user.name;
      this.formEmail = user.email;
      this.isEditMode = true;
    },
    resetForm() {
      this.editingUserId = null;
      this.formName = '';
      this.formEmail = '';
      this.isEditMode = false;
    }
  },
  created() {
    this.fetchUsers()
  }
}
</script>

<style scoped>
.form-container, .user-list-container {
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 10px;
  background-color: #fff;
}

.user-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border: 1px solid #ddd;
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 5px;
  background-color: #f9f9f9;
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-info h5 {
  margin: 0;
  font-size: 1.2em;
}

.user-info p {
  margin: 0;
  color: #777;
}

.user-actions {
  display: flex;
  flex-direction: column;
}

.user-actions .btn {
  width: 80px;
}
</style>
