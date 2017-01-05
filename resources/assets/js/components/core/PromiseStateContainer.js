import React, { Component, PropTypes } from 'react'
import { PromiseState } from 'react-refetch'
import Fade from '../transition/Fade'
import Loading from './Loading'
import Error from './Error'

class PromiseStateContainer extends Component {

  static isEmptyObject(object) {
    return Object.keys(object).length === 0
  }

  render() {
    const { ps, onPending, onNoResults, onRejection, onFulfillment } = this.props;

    if (ps.pending) {
      return onPending(ps.meta)
    } else if (ps.rejected) {
      return onRejection(ps.meta)
    } else if (ps.fulfilled && PromiseStateContainer.isEmptyObject(ps.value)) {
      return onNoResults(ps.meta)
    } else if (ps.fulfilled) {
      return (
        <Fade>
          {onFulfillment(ps.value, ps.meta)}
        </Fade>
      )
    } else {
      console.log('invalid promise state', ps);
      return null
    }
  }
}

PromiseStateContainer.propTypes = {
  ps: PropTypes.instanceOf(PromiseState).isRequired,
  onPending: PropTypes.func,
  onNoResults: PropTypes.func,
  onRejection: PropTypes.func,
  onFulfillment: PropTypes.func.isRequired,
};

PromiseStateContainer.defaultProps = {
    onPending: (meta) => <Loading />,
    onNoResults: (meta) => <Error error="No results" />,
    onRejection: (meta) => <Error error="Something went wrong" />,
};

export default PromiseStateContainer
