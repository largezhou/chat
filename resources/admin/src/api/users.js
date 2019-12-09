import axios from '@/plugins/axios'

export function storeUser(data) {
  return axios.post('/users', data)
}

export function getUsers(params) {
  return axios.get('/users', { params })
}

export function updateUser(id, data) {
  return axios.put(`/users/${id}`, data)
}

export function editUser(id) {
  return axios.get(`/users/${id}/edit`)
}

export function destroyUser(id) {
  return axios.delete(`/users/${id}`)
}
