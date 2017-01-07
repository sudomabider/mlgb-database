import React from 'react'

class Loading extends React.Component {

  render() {
    return (
      <div className="text-xs-center w-100 py-3 text-muted">
        <p className="lead">
          <i className="fa fa-spinner fa-pulse fa-spin fa-fw" /> {this.props.text}
        </p>
      </div>
    )
  }

}

Loading.propTypes = {
  text: React.PropTypes.string
};

Loading.defaultProps = {
  text: 'Calculating...'
};

export default Loading
