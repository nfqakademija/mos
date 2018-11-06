import React, { Component, Fragment } from 'react';
import { NavLink } from 'react-router-dom';
import Login from './Login';
import { connect } from 'react-redux';
import {resetToken} from "../actions/auth";

class Menu extends Component {

  renderGuestMenu = () => {
    return (
      <li className="nav-item">
        <a className="nav-link" data-toggle="modal" data-target="#loginModal">
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
          <div className="nav-link" onClick={onLogout}>
            Logout
          </div>
        </li>
        <li className="nav-item">
          <NavLink className="nav-link" to="/profile/view">
            My Profile
          </NavLink>
        </li>
        <li className="nav-item">
          <NavLink className="nav-link" to="/profile/viewlist">
            Profile list
          </NavLink>
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
  onLogout: () => dispatch(resetToken())
});

export default connect(mapStateToProps, mapDispatchToProps)(Menu);