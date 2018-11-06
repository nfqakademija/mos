import C from '../constants';

const initialState = {
  token: null,
  errorMessage: null
};

export default (state = initialState, action) => {
  switch(action.type) {
    case C.SET_TOKEN:
      return {
        errorMessage: null,
        token: action.payload
      };
    case C.RESET_TOKEN:
      return {
        ...state,
        token: null
      };
    case C.SET_ERROR:
      return {
        ...state,
        errorMessage: action.payload
      };
    default:
      return state;
  }
};