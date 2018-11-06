import React, {Component, Fragment} from 'react';

class Profile extends Component {
  render() {
    const {
      username,
      email,
      name,
      surname,
      birth_date,
      region,
      address,
      phone_number,
      role,
      registration_date,
      last_access_date
    } = this.props;

    return (
      <div className="container">
        <h1>Profile view</h1>
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
              <td>Birth date</td>
              <td>{birth_date}</td>
            </tr>
            <tr>
              <td>Region</td>
              <td>{region}</td>
            </tr>
            <tr>
              <td>Address</td>
              <td>{address}</td>
            </tr>
            <tr>
              <td>Phone number</td>
              <td>{phone_number}</td>
            </tr>
            <tr>
              <td>Roles</td>
              <td>{role}</td>
            </tr>
            <tr>
              <td>Registration date</td>
              <td>{registration_date}</td>
            </tr>
            <tr>
              <td>Last access date</td>
              <td>{last_access_date}</td>
            </tr>
          </tbody>
        </table>
      </div>
    )
  }
}

export default Profile;