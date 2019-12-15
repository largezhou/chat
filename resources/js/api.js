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
