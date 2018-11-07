import C from '../constants';
import axios from "axios";
import { closeModal } from "./modal";

export const setToken = token => ({
  type: C.SET_TOKEN,
  payload: token
});

export const resetToken = () => ({
  type: C.RESET_TOKEN
});

export const setError = error => ({
  type: C.SET_ERROR,
  payload: error
});

export const getToken = ({ username, password }) => dispatch => {
  axios
    .post('/api/login', { username, password })
    .then(({ data: { token } }) => {
      dispatch(setToken((token)));
      dispatch(closeModal())
    })
    .catch(({ request: { response } }) => dispatch(setError(JSON.parse(response).message)));
};