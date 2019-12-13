import axios from '@/plugins/axios'

export function getUserFriends(params) {
  return axios.get('/user-friends', { params })
}

export function destroyUserFriend(id) {
  return axios.delete(`/user-friends/${id}`)
}
