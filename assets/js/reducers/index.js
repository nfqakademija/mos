import { combineReducers } from 'redux';
import authReducer from './auth';
import modalReducer from './modal';
import userReducer from './users';

export default combineReducers({
  auth: authReducer,
  modal: modalReducer,
  users: userReducer
});