import C from '../constants';

const initialState = {
  currentUser: {},
  list: []
};

export default (state = initialState, action) => {
  switch(action.type) {
    case C.SET_CURRENT_USER:
      return {
        ...state,
        currentUser: action.payload
      };
    case C.SET_ALL_USERS:
      return {
        ...state,
        list: action.payload
      };
    default:
      return state;
  }
};