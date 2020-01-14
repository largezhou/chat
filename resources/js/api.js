import axios from '@/libs/axios'

export const postLogin = data => {
  return axios.post('/login', data)
}

export const getUserInfo = () => {
  return axios.get('/user/info')
}

export const postLogout = () => {
  return axios.post('/logout')
}

export const getUserFriends = () => {
  return axios.get('/user/friends')
}

export const getFriendsMsgs = id => {
  return axios.get(`/user/friends/${id}/msgs`)
}

export const getRecentContacts = () => {
  return axios.get('/user/recent-contacts')
}

export const storeRecentContact = targetId => {
  return axios.post('/user/recent-contacts', { target_id: targetId })
}

export const storeUserFriend = friendId => {
  return axios.post('/user-friends', { friend_id: friendId })
}

export const getUsers = q => {
  return axios.get('/users', { params: { q } })
}
