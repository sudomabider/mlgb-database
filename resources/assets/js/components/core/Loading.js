import React from 'react'

class Loading extends React.Component {

  render() {
    return (
      <div className="text-xs-center w-100 py-3 text-muted">
        <h1>
          <i className="fa fa-spinner fa-pulse fa-spin fa-fw" /> {this.props.text}
        </h1>
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
