import C from '../constants';
import { resetError } from "./auth";

export const openModal = () => ({
  type: C.OPEN_MODAL
});

export const closeModal = () => ({
  type: C.CLOSE_MODAL
});

export const closeLogin = () => dispatch => {
  dispatch(closeModal());
  dispatch(resetError());
};