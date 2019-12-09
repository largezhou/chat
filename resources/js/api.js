export const postLogin = data => {
  return axios.post('/login', data)
}

export const getUserInfo = () => {
  return axios.get('/users/info')
}

export const postLogout = () => {
  return axios.post('/logout')
}
