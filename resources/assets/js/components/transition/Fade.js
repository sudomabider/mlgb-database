import React from "react"

const Fade = React.createClass({
  render() {
    return (
      <div className="animated fadeIn">
        {this.props.children}
      </div>
    );
  },
});

export default Fade