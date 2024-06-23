/* eslint-disable */
import { createStore } from 'vuex'
import axios from '../axios' // Import the configured Axios instance

export default createStore({
  state: {
    users: [],
    currentUser: { id: null, name: '', email: '' }
  },
  mutations: {
    SET_USERS(state, users) {
      state.users = users
    },
    SET_CURRENT_USER(state, user) {
      state.currentUser = user
    },
    ADD_USER(state, user) {
      state.users.push(user)
    },
    UPDATE_USER(state, updatedUser) {
      const index = state.users.findIndex(user => user.id === updatedUser.id)
      if (index !== -1) {
        state.users.splice(index, 1, updatedUser)
      }
    },
    DELETE_USER(state, userId) {
      state.users = state.users.filter(user => user.id !== userId)
    }
  },
  actions: {
    fetchUsers({ commit }) {
      axios.get('/users').then(response => {
        commit('SET_USERS', response.data)
      }).catch(error => {
        console.error('Error fetching users:', error)
      })
    },
    addUser({ commit }, user) {
      axios.post('/users', user).then(response => {
        // Assuming response contains the new user ID
        commit('ADD_USER', { ...user, id: response.data.id })  
      }).catch(error => {
        console.error('Error adding user:', error)
      })
    },
    updateUser({ commit }, user) {
      axios.put(`/user/${user.id}`, user).then(() => {
        commit('UPDATE_USER', user)
      }).catch(error => {
        console.error('Error updating user:', error)
      })
    },
    deleteUser({ commit }, userId) {
      axios.delete(`/user/${userId}`).then(() => {
        commit('DELETE_USER', userId)
      }).catch(error => {
        console.error('Error deleting user:', error)
      })
    },
    setCurrentUser({ commit }, user) {
      commit('SET_CURRENT_USER', user)
    }
  },
  getters: {
    users: state => state.users,
    currentUser: state => state.currentUser
  }
})
