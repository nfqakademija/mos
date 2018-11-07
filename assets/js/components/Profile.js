import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import {getCurrentUser} from "../actions/users";

class Profile extends Component {
  constructor(props) {
    super(props);

    props.onGetUser();
  }

  render() {
    const {
      currentUser: {
        username,
        email,
        name,
        surname,
        region
      }
    } = this.props;

    return (
      <table className="table">
        <tbody>
          <tr>
            <th scope="col">Label</th>
            <th scope="col">Data</th>
          </tr>
          <tr>
            <td>Username</td>
            <td>{username}</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>{email}</td>
          </tr>
          <tr>
            <td>Name</td>
            <td>{name}</td>
          </tr>
          <tr>
            <td>Surname</td>
            <td>{surname}</td>
          </tr>
          <tr>
            <td>Region</td>
            <td>{region}</td>
          </tr>
        </tbody>
      </table>
    )
  }
}

const mapStateToProps = ({ users: { currentUser } }) => ({
  currentUser
});

const mapDispatchToProps = dispatch => ({
  onGetUser: () => dispatch(getCurrentUser())
});

export default connect(mapStateToProps, mapDispatchToProps)(Profile);