import React, { Component } from 'react';
import { connect } from 'react-redux';
import {getToken, setError} from "../actions/auth";

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

  handleSubmit = e => {
    const { onError } = this.props;
    const { username, password }  = this.state;

    e.preventDefault();
    username.length === 0 || password.length === 0
      ? onError('Visi laukeliai privalo būti užpildyti')
      : this.requestUser();
  };

  requestUser = () => {
    const { onLogin } = this.props;
    const { username, password } = this.state;

    onLogin({ username, password });
  };

  render() {
    const { errorMessage } = this.props;

    return (
      <div className="modal fade" id="loginModal">
        <div className="modal-dialog">
          <div className="modal-content">
            <form onSubmit={this.handleSubmit}>
              <div className="modal-header">
                <h5 className="modal-title">Prisijungimas</h5>
                <button type="button" className="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
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
                <button type="button" className="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
                <button type="submit" className="btn btn-primary">Patvirtinti</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    )
  }
}

const mapStateToProps = ({ auth: { errorMessage } }) => ({
  errorMessage
});

const mapDispatchToProps = dispatch => ({
  onLogin: user => dispatch(getToken(user)),
  onError: message => dispatch(setError(message))
});

export default connect(mapStateToProps, mapDispatchToProps)(Login);