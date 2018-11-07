import React, { Component } from 'react';
import { connect } from 'react-redux';
import { getToken, setError } from "../actions/auth";
import Modal from 'react-modal';
import { closeLogin } from "../actions/modal";

Modal.setAppElement('#root');

class Login extends Component {
  constructor() {
    super();

    this.state = {
      username: '',
      password: ''
    };
  }

  handleChange = ({ target: { name, value } }) => {
    this.setState({
      [name]: value
    })
  };

  resetInputs = () => {
    this.setState({
      username: '',
      password: ''
    });
  };

  handleSubmit = e => {
    const { onError, onLogin } = this.props;
    const { username, password }  = this.state;

    e.preventDefault();
    this.resetInputs();
    username.length === 0 || password.length === 0
      ? onError('Fields must not be empty')
      : onLogin({ username, password });
  };

  closeModal = () => {
    const { onClose } = this.props;

    this.resetInputs();
    onClose();
  };

  render() {
    const { errorMessage, open } = this.props;

    return (
      <Modal
        className="modal-dialog"
        isOpen={open}
        overlayClassName="loginModal"
        onRequestClose={this.closeModal}
      >
         <div className="modal-content">
           <form onSubmit={this.handleSubmit}>
             <div className="modal-header">
               <h5 className="modal-title">Prisijungimas</h5>
               <button type="button" className="close">
                 <span onClick={this.closeModal}>&times;</span>
               </button>
             </div>
             <div className="modal-body">
               {errorMessage && <div className="alert alert-danger">{errorMessage}</div>}
               <div className="form-group">
                 <label htmlFor="username">Vartotojo vardas</label>
                 <input
                   type="text"
                   name="username"
                   placeholder="Vartotojo vardas"
                   onChange={this.handleChange}
                   className="form-control"
                   id="username"
                 />
               </div>
               <div className="form-group">
                 <label htmlFor="password">Slaptažodis</label>
                 <input
                   type="password"
                   name="password"
                   placeholder="Slaptažodis"
                   onChange={this.handleChange}
                   className="form-control"
                   id="password"
                 />
               </div>
             </div>
             <div className="modal-footer">
               <button type="button" className="btn btn-secondary" onClick={this.closeModal}>Uždaryti</button>
               <button type="submit" className="btn btn-primary">Patvirtinti</button>
             </div>
           </form>
         </div>
      </Modal>
    )
  }
}

const mapStateToProps = ({ auth: { errorMessage }, modal }) => ({
  errorMessage,
  open: modal
});

const mapDispatchToProps = dispatch => ({
  onLogin: user => dispatch(getToken(user)),
  onError: message => dispatch(setError(message)),
  onClose: () => dispatch(closeLogin())
});

export default connect(mapStateToProps, mapDispatchToProps)(Login);