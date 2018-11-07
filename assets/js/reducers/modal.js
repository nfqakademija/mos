import C from '../constants';

export default (state = false, action) => {
  switch(action.type) {
    case C.OPEN_MODAL:
      return true;
    case C.CLOSE_MODAL:
      return false;
    default:
      return state;
  }
};