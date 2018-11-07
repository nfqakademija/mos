import React, { Component, Fragment } from 'react';
import { Link } from 'react-router-dom';
import Login from './Login';
import { connect } from 'react-redux';
import {resetToken} from "../actions/auth";
import {openModal} from "../actions/modal";

class Menu extends Component {

  renderGuestMenu = () => {
    const { onOpen } = this.props;

    return (
      <li className="nav-item">
        <a className="nav-link" onClick={onOpen}>
          Login
        </a>
      </li>
    )
  };

  renderUserMenu = () => {
    const { onLogout } = this.props;

    return (
      <Fragment>
        <li className="nav-item">
          <Link className="nav-link" to="/profile/view">
            My Profile
          </Link>
        </li>
        <li className="nav-item">
          <Link className="nav-link" to="/profile/viewlist">
            Profile list
          </Link>
        </li>
        <li className="nav-item">
          <Link className="nav-link" to="/" onClick={onLogout}>
            Logout
          </Link>
        </li>
      </Fragment>
    )
  };

  render() {
    const { loggedIn } = this.props;

    return (
      <Fragment>
        <nav className="navbar navbar-dark bg-dark fixed-top navbar-expand">
          <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
            <span className="navbar-toggler-icon" />
          </button>
          <div className="collapse navbar-collapse" id="navbar">
            <ul className="navbar-nav">
              <li className="nav-item">
                <Link className="nav-link" to="/">
                  Home
                </Link>
              </li>
              {!loggedIn ? this.renderGuestMenu() : this.renderUserMenu()}
            </ul>
          </div>
        </nav>
        <Login />
      </Fragment>
    )
  }
}

const mapStateToProps = ({ auth: { token } }) => ({
  loggedIn: !!token
});

const mapDispatchToProps = dispatch => ({
  onLogout: () => dispatch(resetToken()),
  onOpen: () => dispatch(openModal())
});

export default connect(mapStateToProps, mapDispatchToProps)(Menu);