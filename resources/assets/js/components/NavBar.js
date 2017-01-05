import React from 'react'
import { Link } from 'react-router'
import { Navbar, NavbarBrand, Nav, NavItem, NavLink } from 'reactstrap'

class NavBar extends React.Component {

  render() {
    return (
      <Navbar color="primary" dark={true}>
        <div className="container">
          <Nav navbar>
            <NavItem>
              <Link className="nav-link" to="/" activeClassName="active" onlyActiveOnIndex={true}>Home</Link>
            </NavItem>
            <NavItem>
              <Link className="nav-link" to="/players" activeClassName="active">Players</Link>
            </NavItem>
          </Nav>
        </div>
      </Navbar>
    );
  }
}

NavBar.contextTypes = { router: React.PropTypes.object };

export default NavBar