import C from '../constants';
import axios from "axios";

export const setCurrentUser = user => ({
  type: C.SET_CURRENT_USER,
  payload: user
});

export const setUserList = users => ({
  type: C.SET_ALL_USERS,
  payload: users
});

export const getCurrentUser = () => (dispatch, getState) => {
  axios
    .get('/api/profile/view', { headers: {"Authorization": `Bearer ${getState().auth.token}`} })
    .then(({ data }) => dispatch(setCurrentUser(data)));
};

export const getAllUsers = () => (dispatch, getState) => {
  axios
    .get('/api/profile/viewlist', { headers: {"Authorization": `Bearer ${getState().auth.token}`} })
    .then(({ data }) => dispatch(setUserList(data)));
};