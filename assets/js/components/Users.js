import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import {getAllUsers, getCurrentUser} from "../actions/users";

class Profile extends Component {
  constructor(props) {
    super(props);

    props.onGetUsers();
  }

  renderUser = ({ username, email, name, surname }) => {
    return (
      <tr key={username}>
        <td>{username}</td>
        <td>{email}</td>
        <td>{name}</td>
        <td>{surname}</td>
      </tr>
    )
  };

  render() {
    const { users } = this.props;

    return (
      <table className="table">
        <tbody>
        <tr>
          <th scope="col">Username</th>
          <th scope="col">Email</th>
          <th scope="col">Name</th>
          <th scope="col">Surname</th>
        </tr>
        {users.map(this.renderUser)}
        </tbody>
      </table>
    )
  }
}

const mapStateToProps = ({ users: { list } }) => ({
  users: list
});

const mapDispatchToProps = dispatch => ({
  onGetUsers: () => dispatch(getAllUsers())
});

export default connect(mapStateToProps, mapDispatchToProps)(Profile);