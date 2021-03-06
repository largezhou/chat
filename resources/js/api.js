import Request from '@/libs/request'

export const postLogin = data => {
  return Request.post('/login', data)
    .setConfig({ showValidationError: false })
}

export const getUserInfo = () => {
  return Request.get('/user/info')
}

export const postLogout = () => {
  return Request.post('/logout')
}

export const getUserFriends = () => {
  return Request.get('/user/friends')
}

export const getFriendsMsgs = id => {
  return Request.get(`/user/friends/${id}/msgs`)
}

export const getRecentContacts = () => {
  return Request.get('/user/recent-contacts')
}

export const storeRecentContact = targetId => {
  return Request.post('/user/recent-contacts', { target_id: targetId })
}

export const storeUserFriend = friendId => {
  return Request.post('/user-friends', { friend_id: friendId })
}

export const getUsers = q => {
  return Request.get('/users', { params: { q } })
}
