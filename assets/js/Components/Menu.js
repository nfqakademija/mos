import React, { Component, Fragment } from 'react';
import { NavLink } from 'react-router-dom';
import Login from './Login';

class Menu extends Component {
  render() {
    return (
      <Fragment>
        <nav className="navbar navbar-dark bg-dark fixed-top navbar-expand">
          <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
            <span className="navbar-toggler-icon" />
          </button>
          <div className="collapse navbar-collapse" id="navbar">
            <ul className="navbar-nav">
              <li className="nav-item float-right">
                <a className="nav-link" data-toggle="modal" data-target="#loginModal">
                  Login
                </a>
              </li>
              <li className="nav-item">
                <NavLink className="nav-link" to="/logout">
                  Logout
                </NavLink>
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
            </ul>
          </div>
        </nav>
        <Login />
      </Fragment>
    )
  }
}

export default Menu;